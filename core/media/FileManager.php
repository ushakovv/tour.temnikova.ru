<?php

namespace core\media;


use core\components\TranslitService;
use yii\db\Query;
use yii\validators\Validator;
use yii\web\UploadedFile;

class FileManager extends \yii\base\Component
{
    public $urlPrefix = "/static";

    /**
     * @return \creocoder\flysystem\LocalFilesystem
     */
    public static function getFs()
    {
        return \Yii::$app->fs;
    }

    public function put($type, $content, $filename, $folder = null)
    {
        if (!$folder) {
            $folder = substr(md5(uniqid("", true)), 0, 6);
        }
        $safeFilename = TranslitService::cyrillicToLatin($filename, false);
        $path = "/f/$type/$folder/$safeFilename";
        $this->getFs()->put($path, $content);
        return \Yii::$app->fileManager->urlPrefix . $path;
    }

    public function getUrl($path)
    {
        return $path;
    }

    public static function attachMediaFile($path, $object_type, $object_id, $object_subtype = 'main')
    {
        \Yii::$app->db->createCommand()->insert('media_files', [
            'object_id' => $object_id,
            'object_type' => $object_type,
            'object_subtype' => $object_subtype,
            'file' => $path
        ])->execute();
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @param $attribute
     * @param $value
     * @return array
     * @throws \yii\base\Exception
     */
    public static function processPostField($model, $attribute, $value, $options)
    {
        $result = null;
        $validatorType = isset($options['validator']) ? $options['validator'] : 'file';
        $validatorOptions = isset($options['validatorOptions']) ? $options['validatorOptions'] : [];
        $modelName = $model->formName();
        $file = UploadedFile::getInstanceByName($modelName . "[" . $attribute . "]");
        if($file){
            $validator = Validator::createValidator($validatorType, $model, $attribute, $validatorOptions);
            $error = null;
            if($validator->validate($file, $error)){
                $type = $model::tableName();
                if (is_array($file)) {
                    /*if (!empty($tmp_name) && $tmp_name[0]) {
                        foreach ($tmp_name as $i => $tn) {
                            $filename = $_FILES[$modelName]['name'][$attribute][$i];
                            $content = file_get_contents($tn);
                            $path = \Yii::$app->fileManager->put($options['type'] . "." . $options['subtype'], $content, $filename, isset($options['options']) && isset($options['options']['folder']) ? $options['options']['folder'] : null);
                            self::attachMediaFile($path, $options['type'], $options['id'], $options['subtype']);
                        }
                    }*/
                } else {
                    $filename = $file->name;
                    $content = file_get_contents( $file->tempName );
                    $result = \Yii::$app->fileManager->put($type, $content, $filename);
                }
            } else {
                $model->addError($attribute, $error);
            }
        }
        return $result;
    }

    public static function getMediaFiles($object_type, $object_id, $object_subtype = 'main')
    {
        $files = (new Query())
            ->select(['file'])
            ->from('media_files')
            ->where([
                'object_type' => $object_type,
                'object_id' => $object_id,
                'object_subtype' => $object_subtype,
                'enabled' => 1
            ])->orderBy(['ord' => 'ASC', 'id' => 'ASC'])
            ->column();
        foreach ($files as $i => $file) {
            $files[$i] = self::getUrl($file);
        }
        return $files;
    }
} 