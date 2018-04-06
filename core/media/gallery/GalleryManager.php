<?php

namespace core\media\gallery;

use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Widget to manage gallery.
 * Requires Twitter Bootstrap styles to work.
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
class GalleryManager extends Widget
{
    /** @var \app\components\ActiveRecord */
    public $model;

    /** @var string */
    public $subtype;

    /** @var string Route to gallery controller */
    public $apiRoute = false;

    public $name;

    public $options = array();


    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['galleryManager/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@zxbodya/yii2/galleryManager/messages',
            'fileMap' => [],
        ];
    }


    /** Render widget */
    public function run()
    {
        if ($this->apiRoute === null) {
            throw new Exception('$apiRoute must be set.', 500);
        }

        $images = [];
        
        foreach ($this->model->getImages() as $image) {
            $images[] = array(
                'id' => $image->id,
                'rank' => $image->ord,
                'name' => (string)$image->title,
                'description' => '',
                'preview' => $image->getUrl('200x200'),
            );
        }

        $modelClass = $this->model->className();

        $baseUrl = [
            $this->apiRoute,
            'model' => $modelClass,
            'owner_id' => $this->model->id,
            'subtype' => $this->subtype
        ];

        $opts = array(
            'hasName' => false,
            'hasDesc' => false,
            'uploadUrl' => Url::to($baseUrl + ['action' => 'ajaxUpload']),
            'deleteUrl' => Url::to($baseUrl + ['action' => 'delete']),
            'updateUrl' => Url::to($baseUrl + ['action' => 'changeData']),
            'arrangeUrl' => Url::to($baseUrl + ['action' => 'order']),
            'nameLabel' => 'Name',
            'descriptionLabel' => 'Description',
            'photos' => $images,
        );

        $opts = Json::encode($opts);
        $view = $this->getView();
        GalleryManagerAsset::register($view);
        $view->registerJs("$('#{$this->id}').galleryManager({$opts});");

        $this->options['id'] = $this->id;
        $this->options['class'] = 'gallery-manager';

        return $this->render('galleryManager');
    }

}
