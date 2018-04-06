<?php

namespace core\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
	public $sourcePath = '@core/assets/app-asset/';
    public $baseUrl = '@web';
    public $css = [
	    'css/core-main.css',
	    'css/filetypes.css',
	    'css/the-modal.css'
    ];
    public $js = [
	    'js/jquery.the-modal.js',
	    'js/core-main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
