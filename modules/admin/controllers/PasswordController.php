<?php

namespace admin\controllers;

use admin\models\AdminUserPasswordHistory;

class PasswordController extends \admin\components\AdminController
{

	public function actionIndex()
	{
		$error = '';
		$success = '';
		$model = \Yii::$app->user->identity;

		if (isset($_POST['password'])) {
		//	var_dump($_POST); exit;
			if(!(preg_match('/[0-9]/', $_POST['password'])
				&& preg_match('/[a-zA-Zа-яА-Я]/', $_POST['password'])
				&& preg_match('/[^0-9a-zA-Zа-яА-Я]/', $_POST['password'])))
			{
				$error = "Пароль должен содержать буквы, цифры и символы";
			}
			if(strlen($_POST['password']) < 8) {
				$error = 'Пароль должен быть не менее 8 символов';
			}
			if(!$_POST['password']) {
				$error = 'Необходимо ввести новый пароль';
			}
			if(!$_POST['oldPassword']){
				$error = 'Необходимо ввести старый пароль';
			}
			if(!$_POST['oldPassword'] || !\Yii::$app->security->validatePassword($_POST['oldPassword'],  $model->password_hash)){
				$error = 'Введен неверный старый пароль';
			}
			if($error=="") {
				$model->password_hash = \Yii::$app->security->generatePasswordHash($_POST['password']);

				// Проверка был ли ранее использован данный пароль
				$password_history = AdminUserPasswordHistory::find()->where([
					"admin_user_id"=>$model->id,
					"password"=>  md5($_POST['password']),
				])->all();
				if($password_history || $_POST['oldPassword'] == $_POST['password']) {
					$error = "Вы уже ранее использовали такой пароль, попробуйте придумать другой";
				}
				else {
					$model->dt_change_password = date("Y-m-d H:i:s");
					$model->save();

					\Yii::$app->user->login($model, 0);

					//$this->needChangePassword = false;

					// Сохраняем пароль в историю
					$password_history = new AdminUserPasswordHistory();
					$password_history->admin_user_id = $model->id;
					$password_history->password = md5($_POST['oldPassword']);
					$password_history->save();

					$success = 'Пароль успешно изменен';
				}
			}
		}
//		if(isset($_GET['User'])) {
//			$model->attributes=$_GET['User'];
//		} else {
//			$model->attributes=array('is_blocked'=>'','profile_type'=>'');
//		}
//
		echo $this->render('index', array(
				'model'=>$model, 'error'=>$error,'success'=>$success,
			)
		);
	}

}
