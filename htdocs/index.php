<?php

$debugMode = false;

// comment out the following two lines when deployed to production
if($debugMode){
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_ENV') or define('YII_ENV', 'dev');
    defined('YII_ENV_DEV') or define('YII_ENV_DEV', true);
}

session_set_cookie_params ( 1800, "/", null, false, true);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$host = @$_SERVER['HTTP_HOST'];
$localConfigFile = 'local.php';

$config = yii\helpers\ArrayHelper::merge(
	require(__DIR__ . '/../config/web.php'),
	require(__DIR__ . '/../config/' . $localConfigFile)
);

include "../functions.php";

(new yii\web\Application($config))->run();
