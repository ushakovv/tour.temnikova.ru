<?php

namespace core\media\gallery;

use Yii;
use yii\web\AssetBundle;

class GalleryManagerAsset extends AssetBundle
{
    public $sourcePath = '@app/core/media/gallery/assets';
    public $js = [
        'jquery.iframe-transport.js',
        'jquery.galleryManager.js',
    ];
    public $css = [
        'galleryManager.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];
}
