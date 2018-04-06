<?php
/**
 * Date: 18.01.14
 * Time: 22:16
 */

namespace core\ckeditor;

use yii\web\AssetBundle;


class AssetsJQueryAdapter extends AssetBundle{

	public $sourcePath = '@core/ckeditor/editor/adapters';

    public $js = [
        'jquery.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'core\ckeditor\Assets'
    ];
}