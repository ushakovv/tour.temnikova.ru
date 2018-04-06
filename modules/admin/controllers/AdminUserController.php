<?php

namespace admin\controllers;

use Yii;
use core\components\UpdateScenario;
use core\components\RoleService;
use yii\web\HttpException;

/**
 * AdminUserController implements the CRUD actions for AdminUser model.
 */
class AdminUserController extends \admin\components\AdminController
{
	public $modelClass = '\app\models\AdminUser';
	public $searchModelClass = '\app\models\search\AdminUserSearch';

	public $sectionTitle = 'Администраторы';

	/**
	 * Updates an existing model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$user = $this->getUser();
		if(!RoleService::checkUserPermission($user->role_id, $_SERVER['REDIRECT_URL'], ['u'=>1])) {
			throw new HttpException(403, 'Forbidden');
		}

		$model = $this->findModel($id);

		$postData = Yii::$app->request->post();

		if(!empty($postData['password'])) {
			$model->setPassword($postData['password']);
			$model->generateAuthKey();
		}

		$attributes = isset($postData[$model->formName()]) ? $postData[$model->formName()] : null;

		if($attributes){
			$attributes = UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', false);
			$this->update($model, $attributes);
		}

		if ($attributes && $model->save()) {
			$attributes = UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', true);
			return $this->redirect(['update', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
}
