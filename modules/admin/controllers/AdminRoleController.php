<?php

namespace admin\controllers;

use Yii;
use core\components\UpdateScenario;
use core\components\RoleService;
use yii\web\HttpException;

/**
 * AdminRoleController implements the CRUD actions for AdminRole model.
 */
class AdminRoleController extends \admin\components\AdminController
{
	public $modelClass = '\app\models\AdminRole';
	public $searchModelClass = '\app\models\search\AdminRoleSearch';

	public $sectionTitle = 'Роли в CMS';

	public function actionUpdate($id)
	{
		$user = $this->getUser();
		if(!RoleService::checkUserPermission($user->role_id, $_SERVER['REDIRECT_URL'], ['u'=>1])) {
			throw new HttpException(403, 'Forbidden');
		}

		$model = $this->findModel($id);

		$postData = Yii::$app->request->post();

		$attributes = isset($postData[$model->formName()]) ? $postData[$model->formName()] : null;

		if($attributes){
			$attributes = UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', false);
			$this->update($model, $attributes);
		}

		if ($attributes && $model->save()) {
			// Сохранение прав
			$section_permissions = [];
			if(isset($_POST['Section_permissions'])) {
				foreach($_POST['Section_permissions'] as $section_id => $permissions) {
					$section_permissions[$section_id] = ((isset($permissions['c']) && $permissions['c']=='on')?'1':'0')
						.((isset($permissions['r']) && $permissions['r']=='on')?'1':'0')
						.((isset($permissions['u']) && $permissions['u']=='on')?'1':'0')
						.((isset($permissions['d']) && $permissions['d']=='on')?'1':'0');
				}
			}
			RoleService::saveRolePermissions($model->id, $section_permissions);


			UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', true);
			return $this->redirect(['update', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

}
