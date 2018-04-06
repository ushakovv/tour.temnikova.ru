<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

if(file_exists(__DIR__ . '/local.php')){
    $local = require(__DIR__ . '/local.php');
} else {
    $local = null;
}


return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@core' => '@app/core',
        '@admin' => '@app/modules/admin',
        '@static' => '@app/htdocs/files',
        '@webroot' => '@app/htdocs',
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $local ? $local['components']['db'] : null,
        'imageProcessor' => [
            'class' => 'core\media\ImageProcessorGd'
        ],
        'imageManager' => [
            'class' => 'core\media\ImageManager',
            'config' => include('image.php')
        ],
        'fs' => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@webroot/files',
        ],
    ],
    'params' => [
        'host' => 'http://pewete.dev.promo.ru'
    ]
];
