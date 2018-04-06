<?php

namespace admin\controllers;

use admin\models\LoginForm;

class DefaultController extends \admin\components\AdminController
{
    public function actionIndex()
    {
       // $this->redirect("/crt7E9sx42/");
        return $this->render('index');
    }

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(\Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	public function actionLogout()
	{
		\Yii::$app->user->logout();

		return $this->goHome();
	}
}
