<?php

namespace core\media\gallery;


use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Backend controller for GalleryManager widget.
 * Provides following features:
 *  - Image removal
 *  - Image upload/Multiple upload
 *  - Arrange images in gallery
 *  - Changing name/description associated with image
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
class GalleryManagerAction extends Action
{
    /**
     * Glue used to implode composite primary keys
     * @var string
     */
    public $pkGlue = '_';

    /**
     * $types to be defined at Controller::actions()
     * @var array Mapping between types and model class names
     * @example 'post'=>'common\models\Post'
     * @see     GalleryManagerAction::run
     */
    public $types = [];


    protected $type;
    protected $behaviorName;
    protected $galleryId;

    /** @var  ActiveRecord */
    protected $model;

    protected $subtype;


    public function run($action)
    {
        $modelClass = Yii::$app->request->get('model');
        $owner_id = Yii::$app->request->get('owner_id');
        $model = $modelClass::findOne($owner_id);
        $this->model = $model;
        $this->subtype = Yii::$app->request->get('subtype');

        switch ($action) {
            case 'delete':
                return $this->actionDelete(Yii::$app->request->post('id'));
                break;
            case 'ajaxUpload':
                return $this->actionAjaxUpload();
                break;
            case 'changeData':
                return $this->actionChangeData(Yii::$app->request->post('photo'));
                break;
            case 'order':
                return $this->actionOrder(Yii::$app->request->post('order'));
                break;
            default:
                throw new HttpException(400, 'Action do not exists');
                break;
        }
    }

    /**
     * Removes image with ids specified in post request.
     * On success returns 'OK'
     *
     * @param $ids
     *
     * @throws HttpException
     * @return string
     */
    protected function actionDelete($imageIds)
    {

        foreach ($imageIds as $imageId) {
            \Yii::$app->imageManager->deleteFromGallery($imageId);
        }
        /*if ($this->_images !== null) {
            $removed = array_combine($imageIds, $imageIds);
            $this->_images = array_filter(
                $this->_images,
                function ($image) use (&$removed) {
                    return !isset($removed[$image->id]);
                }
            );
        }*/

        return 'OK';
    }

    /**
     * Method to handle file upload thought XHR2
     * On success returns JSON object with image info.
     *
     * @return string
     * @throws HttpException
     */
    public function actionAjaxUpload()
    {

        $imageFile = UploadedFile::getInstanceByName('image');

        $imgContent = file_get_contents($imageFile->tempName);

        $igRow = \Yii::$app->imageManager->putToGallery(
            $this->model->tableName(),
            $imgContent,
            $this->model->id,
            $this->subtype
        );

        Yii::$app->response->headers->set('Content-Type', 'text/html');

        return Json::encode([
            'id' => $igRow->id,
            'rank' => $igRow->ord,
            'name' => (string)$igRow->title,
            'description' => '',
            'preview' => $igRow->getUrl('200x200')
        ]);
    }

    /**
     * Saves images order according to request.
     *
     * @param array $order new arrange of image ids, to be saved
     *
     * @return string
     * @throws HttpException
     */
    public function actionOrder($order)
    {
        if (count($order) == 0) {
            throw new HttpException(400, 'No data, to save');
        }

        $orders = [];
        $i = 0;
        foreach ($order as $k => $v) {
            if (!$v) {
                $order[$k] = $k;
            }
            $orders[] = $order[$k];
            $i++;
        }
        sort($orders);
        $i = 0;
        $res = [];
        foreach ($order as $k => $v) {
            $res[$k] = $orders[$i];

            \Yii::$app->db->createCommand()
                ->update(
                    'media_gallery',
                    ['ord' => $orders[$i]],
                    ['id' => $k]
                )->execute();

            $i++;
        }


        return Json::encode($res);

    }

    /**
     * Method to update images name/description via AJAX.
     * On success returns JSON array of objects with new image info.
     *
     * @param $imagesData
     *
     * @throws HttpException
     * @return string
     */
    public function actionChangeData($imagesData)
    {
        if (count($imagesData) == 0) {
            throw new HttpException(400, 'Nothing to save');
        }
        $images = $this->behavior->updateImagesData($imagesData);
        $resp = array();
        foreach ($images as $model) {
            $resp[] = array(
                'id' => $model->id,
                'rank' => $model->rank,
                'name' => (string)$model->name,
                'description' => (string)$model->description,
                'preview' => $model->getUrl('preview'),
            );
        }

        return Json::encode($resp);
    }
}
