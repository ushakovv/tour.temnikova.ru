<?php

namespace admin\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
	public $sourcePath = '@admin/assets/app-asset/';
	public $baseUrl = '@web';
	public $css = [
		'css/styles.css',
		'css/plugins.css',
		'css/theme-5.css',
		'css/admin.css'
	];
	public $js = [
		'js/jquery.cookie.js',
		//'js/bootstrap.min.js',
		'js/modernizr.js',
		'js/perfect-scrollbar.min.js',
		'js/switchery.min.js',
		'js/main.js',
		//'js/admin.js',
	];
	public $depends = [
		'core\assets\AppAsset',
	];
}
