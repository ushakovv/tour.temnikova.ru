<?php

$config = [
    'language' => 'ru',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@core' => '@app/core',
        '@admin' => '@app/modules/admin',
        '@static' => '@app/htdocs/files',
        '@components' => '@app/components'
    ],
    'bootstrap' => ['log'],
    'components' => [
        'vkcomponent' => [
            'class' => 'app\components\vk\VkComponent'
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                // https://vk.com/editapp?id=5721895
                'vkontakte' => [
                    'class' => 'core\authclient\clients\VKontakte',
                    'clientId' => '6196367',
                    'clientSecret' => 'ZFZOTmraULuRYSFYyypj',
                    ],

                // https://developers.facebook.com/apps/1670805403250339/dashboard/
                'facebook' => [
                    'class' => 'core\authclient\clients\Facebook',
                    'clientId' => '1670805403250339',
                    'clientSecret' => 'f995664f29ecf5322231a995f77a8204',
                    ],

                // https://apps.twitter.com/app/13082569/settings
                'twitter' => [
                    'class' => 'core\authclient\clients\Twitter',
                    'attributeParams' => [
                        'include_email' => 'true'
                    ],
                    'consumerKey' => 'Wde3NMGzTcXRPpWkB9xfsdCli',
                    'consumerSecret' => 'jzUiEdjC2U8SHj4R8mDQZPV0nqgFNky0Nvar41g3MbjZs3mhZc',
                ],

                ],
            ],
        'request' => [
            'enableCookieValidation' => true,
            'cookieValidationKey' => 'swYbGQnJGfwiS88UnLDcUUQwsl9eU7S-',
        ],
        'formatter' => [
            'class' => 'core\components\Formatter',
            'sizeFormatBase' => 1000
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/login'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=host;dbname=dbname',
            'username' => 'username',
            'password' => 'password',
            'charset' => 'utf8',
            'enableSchemaCache'=>!YII_DEBUG,
            'schemaCacheDuration'=>3600,
            'schemaCache'=>'cache',

            'enableQueryCache'=>!YII_DEBUG,
            'queryCacheDuration'=>3600,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'i18n' => [
            'translations' => [
                'my*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'imageProcessor' => [
            'class' => 'core\media\ImageProcessorGd'
        ],
        'imageManager' => [
            'class' => 'core\media\ImageManager',
            'config' => include('image.php')
        ],
        'fs' => [
            'class' => 'core\flysystem\LocalFilesystem',
            'path' => '@webroot/files'
        ],
        'fileManager' => [
            'class' => 'core\media\FileManager',
            'urlPrefix' => '/files'
        ],
        'view' => [
            'class' => 'app\components\View',
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    'pluginDirs' => ['@app/smartyPlugins']
                    //'cachePath' => '@runtime/Smarty/cache',
                ],
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap'],
                    'functions' => [
                        new \Twig_SimpleFunction('v', '\core\components\ContentManager::get', ['is_safe' => ['html']]),
                        new \Twig_SimpleFunction('rev', '\core\components\ContentManager::getRev', ['is_safe' => ['html']]),
                        new \Twig_SimpleFunction('daterus', '\core\components\ContentManager::getDate', ['is_safe' => ['html']]),
                    ]
                ],
            ],
        ],
    ],
    'modules' => [
        'm6GaVsjn' => [
            'class' => 'admin\Module',
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
            // other module settings, refer detailed documentation
        ],
    ],
    'params' => [
        'adminEmail' => 'admin@example.com',
        'filesUrl' => '',
        'siteName' => 'Test',
        'mail_from' => 'noreply@promo.ru',
        'site_link' =>   '',
        'https' => false,
        'enableConfirmation' => false,
        'enableUnconfirmedLogin' => true,
        'enableGeneratingPassword' => false,
        'rememberFor' => 3600,
        'defaultLang' => 'ru',
        'host' => ''
    ],
    'on beforeRequest' => function () {
        $path = Yii::$app->request->pathInfo;
        if (substr($path, -1) == '/') {
            $query = Yii::$app->request->queryString !='' ? '?' . Yii::$app->request->queryString : '';
            $path = substr($path, 0, -1);
            $path = '/' . $path . $query;
            Yii::$app->response->redirect( $path, 301)->send();
            exit(1);
        }
        $url = $_SERVER['REQUEST_URI'];
        if (strpos($url, 'index.php') !== false) {
            $query = Yii::$app->request->queryString !='' ? '?' . Yii::$app->request->queryString : '';

            $path = '/' . $path . $query;
            Yii::$app->response->redirect( $path, 301)->send();
            exit(1);
        }
    },

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'richCrud' => [
                'class' => 'core\gii\richCrud\Generator',
                'templates' => [
                    'richCrud2' => '@core/gii/richCrud/default'
                ]
            ]        ]
    ];
}

if(isset($_GET['update_assets'])){
    $config['components']['assetManager']['forceCopy'] = true;
}

return $config;
