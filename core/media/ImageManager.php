<?php

namespace core\media;

use app\models\MediaGallery;
use core\media\models\MediaImage;
use core\media\ImageProcessor;
use core\media\ImageProcessorGd;
use creocoder\flysystem\LocalFilesystem;
use yii\base\Exception;
use yii\db\mssql\PDO;
use yii\httpclient\Client;
use yii\validators\ImageValidator;
use yii\validators\Validator;
use yii\web\UploadedFile;

class ImageManager extends \yii\base\Component
{

    public $urlPrefix = "/files";
    public $config = null;
    private $_contentTypes = array(
        "jpe" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "jpg" => "image/jpeg",
        "png" => "image/png",
        "gif" => "image/gif"
    );
    private $_transport = null;
    private $_processor = null;

    public function init()
    {
    }

    /**
     * @param string $type
     * @param string $imageContent
     * @param string $sPath
     * @param int $object_id
     * @return int
     * @throws ImageProcessorException
     */
    public function put($type, $imageContent, $sPath = null, $object_id = null)
    {
        if ($object_id) {
            $galleryType = $type;
            $type = "gallery." . $galleryType;
        }
        $imageModel = $this->_getModel($type);
        if (!$sPath) {
            $sPath = $this->getOriginalPath($type, $imageModel->id);
        }
        $this->getTransport()->put($sPath, $imageContent);
        $imageModel->src = $sPath;
        $imageModel->processed = 2;
        $imageModel->save();
        //try {
        $this->makeVariants($imageModel);
        $imageModel->processed = 1;
        /*} catch (ImageProcessorException $e){
            $this->_transport->delete($sPath);
            $imageModel->delete();
            throw new ImageProcessorException();
        }*/
        $imageModel->save();

        return $imageModel->id;
    }

    /**
     * @param string $object_type
     * @param string $imageContent
     * @param int $object_id
     * @param string $object_subtype
     * @param string|null $title
     * @return MediaGallery
     */
    public function putToGallery($object_type, $imageContent, $object_id, $object_subtype = 'main', $title = null)
    {
        $type = $object_type . "." . $object_subtype;
        $imageId = $this->put($type, $imageContent);
        
        $igRow = new MediaGallery();
        $igRow->attributes = [
            'object_id' => $object_id,
            'object_type' => $object_type,
            'object_subtype' => $object_subtype,
            'image_id' => $imageId,
            'title' => $title,
            'enabled' => 1
        ];
        $igRow->save(false);
        return $igRow;
    }

    /**
     * @param $type
     * @param $url
     * @return int
     */
    public function putFromUrl($type, $url)
    {
        if(!$url){
            return null;
        }
        $client = new Client();
        $response = $client->get($url)->send();
        if($response->isOk){
            $content = $response->getContent();
            return $this->put($type, $content);
        }
    }

    public function getExtension($type, $variant)
    {
        $extension = "jpg";
        $variants = $this->_getVariants($type);
        if ($variants && isset($variants[$variant]) && isset($variants[$variant]['extension'])) {
            $extension = $variants[$variant]['extension'];
        }
        return $extension;
    }

    public function getOriginalPath($type, $id)
    {
        $hash = substr(md5($id), 0, 2);
        $extension = "jpg";
        return "/i/o/$type/$hash/$id.$extension";
    }

    public function getVariantPath($type, $variant, $id)
    {
        if ($id) {
            $hash = substr(md5($id), 0, 2);
            $extension = $this->getExtension($type, $variant);
            return "/i/p/$type/$hash/$variant/$id.$extension";
        } else {
            return null;
        }
    }

    public function getUrlOriginal($type, $id)
    {
        return $this->urlPrefix . $this->getOriginalPath($type, $id);
    }

    public function getUrl($type, $variant, $id)
    {
        return $this->urlPrefix . $this->getVariantPath($type, $variant, $id);
    }

    public function getVariants($type, $id, $makeUrl = true)
    {
        if ($id) {
            $variantsConfig = $this->_getVariants($type);
            $variants = array();
            if (!is_array($variantsConfig)) {
                throw new \yii\base\Exception("Cannot find config for $type");
            }
            foreach ($variantsConfig as $variant => $variantConfig) {
                $variants[$variant] = $this->getVariantPath($type, $variant, $id);
                if ($makeUrl) {
                    $variants[$variant] = $this->urlPrefix .  $variants[$variant];
                }
            }
            return $variants;
        } else {
            return null;
        }
    }

    /*
     * @param Image $imageModel
     * @throws ImageProcessorException
     */
    public function makeVariants($imageModel, $forceOverwrite = true)
    {
        $variants = $this->_getVariants($imageModel->type);
        if ($variants) {
            foreach ($variants as $variantKey => $variantConfig) {
                $variantPath = $this->getVariantPath($imageModel->type, $variantKey, $imageModel->id);
                if ($forceOverwrite || !$this->getTransport()->has($variantPath)) {
                    $this->processor->process($imageModel->src, $variantPath, $variantConfig);
                }
            }
        }
    }

    public function printImage($content, $extension)
    {
        $mimeType = isset($this->_contentTypes[$extension]) ? $this->_contentTypes[$extension] : ("image/" . $extension);
        header("Content-type: $mimeType");
        exit($content);
    }

    public function delete($id)
    {
        $imageModel = MediaImage::findOne($id);
        if ($imageModel) {
            $imageModel->delete();
            return true;
        } else {
            return false;
        }
    }

    public function deleteFromGallery($id)
    {
        $mgModel = MediaGallery::findOneOr404($id);
        $mgModel->delete();
    }

    /**
     * @return \creocoder\flysystem\LocalFilesystem
     */
    protected function getTransport()
    {
        return \Yii::$app->fs;
    }

    protected function getProcessor()
    {
        if (!$this->_processor) {
            $this->_processor = \Yii::$app->get('imageProcessor', false);
        }
        if (!$this->_processor) {
            $this->_processor = new ImageProcessorGd;
        }
        return $this->_processor;
    }

    private function _getModel($type)
    {
        $model = new MediaImage();
        $model->type = $type;
        $model->save();
        return $model;
    }

    private function _getVariants($type)
    {
        if (!isset($this->config[$type])) {
            return null;
            //throw new CException("ImageManager: unknown type [$type]");
        }
        return $this->config[$type];
    }




    /**
     * @param \yii\db\ActiveRecord $model
     * @param $attribute
     * @param $value
     * @return array
     * @throws \yii\base\Exception
     */
    public static function processPostField($model, $attribute, $value)
    {
        $result = null;
        $modelName = $model->formName();
        $file = UploadedFile::getInstanceByName($modelName . "[" . $attribute . "]");
        $error = null;
        if($file){
            if((new ImageValidator())->validate($file, $error)){
                $type = $model::tableName() . "." . $attribute;
                if (isset(\Yii::$app->imageManager->config[$type])) {
                    $content = file_get_contents($file->tempName);
                    $result = \Yii::$app->imageManager->put($type, $content);
                } else {
                    throw new \yii\base\Exception("ImageManager: Configuration not found: $type");
                }
            } else {
                $model->addError($attribute, $error);
            }
        }

        return $result;
    }

    public function generateAllPreviews($forceOverwrite = false, $verbose = false)
    {
        $images = MediaImage::find()->all();
        foreach ($images as $image) {
            $this->makeVariants($image, $forceOverwrite);
            if ($verbose) {
                echo ".";
            }
        }
    }

    /**
     * @param $type
     * @param $imageContent
     * @param $variant
     * @param int|null $image_id
     * @return int
     */
    public function updateVariant($type, $imageContent, $variant, $image_id = null)
    {
        $oldImageModel = null;
        if($image_id){
            $oldImageModel = MediaImage::findOne($image_id);
        }


        $imageModel = $this->_getModel($type);
        $imageModel->src = $oldImageModel ? $oldImageModel->src : null;
        $imageModel->processed = 2;
        $imageModel->save();
        $variants = $this->_getVariants($imageModel->type);

        if($oldImageModel){
            foreach ($variants as $variantKey => $variantConfig){
                $oldPath = $this->getVariantPath($imageModel->type, $variantKey, $oldImageModel->id);
                $newPath = $this->getVariantPath($imageModel->type, $variantKey, $imageModel->id);
                $transport = $this->getTransport();
                $transport->rename($oldPath, $newPath);
            }
            $oldImageModel->delete();
        }
        //try {

        if (isset($variants[$variant])) {
            $variantConfig = $variants[$variant];
            
            $variantPath = $this->getVariantPath($imageModel->type, $variant, $imageModel->id);
            $this->getProcessor()->create($imageContent)->process(null, $variantPath, $variantConfig);
        } else {
            throw new Exception("Undefined variant $variant for type $type");
        }

        $imageModel->processed = 1;
        $imageModel->save();

        return $imageModel->id;
    }


}