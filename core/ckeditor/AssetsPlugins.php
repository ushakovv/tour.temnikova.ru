<?php

namespace core\ckeditor;

use yii\web\AssetBundle;

class AssetsPlugins extends AssetBundle{
    public function init()
    {
        $this->sourcePath = __DIR__ . "/plugins";
        parent::init();
    }

    public $depends = [
        'core\ckeditor\Assets',
    ];
}