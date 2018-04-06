<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

function url($url = '', $scheme = false)
{
    return Url::to($url, $scheme);
}

function t($message, $params = [], $category = 'app', $language = null)
{
    return Yii::t($category, $message, $params, $language);
}

function param($name, $default = null)
{
    return ArrayHelper::getValue(Yii::$app->params, $name, $default);
}

function d($dumpObject, $andExit = true){
    VarDumper::dump($dumpObject, 10, true);
    exit;
}