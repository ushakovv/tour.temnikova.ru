<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;port=3333;dbname=tour;',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
    'params' => [
        'siteLink' => '',
        'adminEmail' => 'no-reply@promo.ru',
    ]
];