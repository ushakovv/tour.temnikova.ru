<?php

namespace core\components;

use admin\models\AdminRole;
use Yii;
use yii\base\Component;

class RoleService extends Component
{
	public static function saveRolePermissions($role_id=null, $section_permissions = []) {
		if($role_id) {
			Yii::$app->db->createCommand(
				'DELETE FROM `admin_role2section`'
				.' WHERE `role_id`=:role_id'
			)->bindParam(':role_id', $role_id)->execute();
			if($section_permissions) {
				foreach($section_permissions as $section_id => $crud_permissions) {
					Yii::$app->db->createCommand(
						'INSERT INTO `admin_role2section` (`role_id`, `section_id`, `crud_permissions`)'
						.' VALUES (:role_id, :section_id, :crud_permissions);'
					)
						->bindParam(':role_id', $role_id)
						->bindParam(':section_id', $section_id)
						->bindParam(':crud_permissions', $crud_permissions)
						->execute();
				}
			}
		}
	}

	public static function checkUserPermission($role_id, $uri, $crud = ['c'=>1, 'r'=>1, 'u'=>1, 'd'=>1], $return_crud=false) {
		if($role_id == AdminRole::FULL_ACCESS){
			if($return_crud){
				return [ 'c' => '1', 'r' => '1', 'u' => '1', 'd' => '1' ];
			} else {
				return true;
			}

		}

		$role_permissions = Yii::$app->db->createCommand(
			'SELECT `admin_section`.`uri`, `admin_role2section`.`crud_permissions` as `crud_permissions` FROM `admin_section`'
			.' INNER JOIN `admin_role2section` ON `admin_section`.`id`=`admin_role2section`.`section_id`'
			.' WHERE `admin_section`.`enabled`=1 AND `admin_role2section`.`role_id`=:role_id'
		)->bindParam(':role_id', $role_id)->queryAll();

		$permissions = '0000';
		$uri = $uri.'/';
		foreach($role_permissions as $permission) {
			if(strpos($uri, '/'.$permission['uri'].'/')!==false) {
				$permissions = $permission['crud_permissions'];
				break;
			}
		}

		$crud_permissions = [
			'c' => substr($permissions, 0, 1),
			'r' => substr($permissions, 1, 1),
			'u' => substr($permissions, 2, 1),
			'd' => substr($permissions, 3, 1),
		];

		if($return_crud) {
			return $crud_permissions;
		}

		$can_access = true;
		foreach($crud as $action => $permission) {
			if($permission && !$crud_permissions[$action]) {
				$can_access = false;
				break;
			}
		}

		return $can_access;
	}
}