<?php

namespace admin;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'admin\controllers';

	public $layout = 'main';

    public function init()
    {
	    parent::init();

	    $request = \Yii::$app->getRequest();

	    /*\Yii::$app->set('request', [
		    'baseUrl' => '/crt7E9sx42',
		    'cookieValidationKey' => $request->cookieValidationKey,
		    'class' => 'yii\web\Request'
	    ]);*/

		\Yii::$app->setHomeUrl("/{$this->id}/");

		\Yii::$app->set('user', [
			'identityClass' => 'admin\models\AdminUser',
			'enableAutoLogin' => true,
			'loginUrl' => '/' . $this->id . '/default/login',
			'class' => 'yii\web\User'
		]);



        // custom initialization code goes here
    }
}
