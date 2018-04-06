<?php

namespace admin\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_section_group".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ord
 * @property integer $enabled
 *
 * @property AdminSection[] $adminSections
 */
class AdminSectionGroup extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'admin_section_group';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['ord', 'enabled'], 'integer'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Название группы',
			'ord' => 'Порядок вывода',
			'enabled' => 'Публиковать',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAdminSections()
	{
		return $this->hasMany(AdminSection::className(), ['section_group_id' => 'id']);
	}

	// $crud_permissions = ['c'=>1, 'r'=>1, 'u'=>1, 'd'=>1]
	public static function getCmsStructure($role_id=null, $crud_permissions=null) {
		$out = [];

		if($role_id == AdminRole::FULL_ACCESS){
			$crud_permissions = null;
		}

		if($role_id && $crud_permissions) {
			$section_groups = Yii::$app->db->createCommand(
				'SELECT `asg`.`id`, `asg`.`name` FROM `admin_section_group` as `asg`'
				.' LEFT JOIN `admin_section` as `as` ON `as`.`section_group_id`=`asg`.`id`'
				.' INNER JOIN `admin_role2section` as `ar2s` ON `ar2s`.`section_id`=`as`.id'
				.' WHERE `asg`.`enabled`=1'
				.' AND `ar2s`.`role_id`='.$role_id.' AND SUBSTR(`ar2s`.`crud_permissions`, 1, 1)=1'
				.' GROUP BY `asg`.`id`'
				.' ORDER BY `asg`.`ord` ASC, `asg`.`name` ASC'
			)->queryAll();
		}
		else {
			$section_groups = Yii::$app->db->createCommand(
				'SELECT `id`, `name` FROM `admin_section_group`'
				.' WHERE `enabled`=1'
				.' ORDER BY `ord` ASC, `name` ASC'
			)->queryAll();
		}


		if($section_groups) {
			$aSectionGroupIds = [];
			foreach($section_groups as $section_group) {
				$aSectionGroupIds[] = $section_group['id'];
				$out[$section_group['id']] = [
					'name' => $section_group['name'],
					'sections' => [],
				];
			}

			if($aSectionGroupIds) {
				$sections = Yii::$app->db->createCommand(
					'SELECT `id`, `name`, `uri`, `is_crud_section`, `section_group_id` FROM `admin_section`'
					.' WHERE `enabled`=1 AND `section_group_id` IN ('.implode(',', $aSectionGroupIds).')'
					.' ORDER BY `ord` ASC, `name` ASC'
				)->queryAll();

				if($sections) {
					if($role_id) {
						$admin_role2section = Yii::$app->db->createCommand(
							'SELECT `section_id`, `crud_permissions` FROM `admin_role2section`'
							.' WHERE `role_id`=:role_id'
						)->bindParam(':role_id', $role_id)->queryAll();

						$permissions = [];
						if($admin_role2section) {
							foreach($admin_role2section as $role2section) {
								$permissions[$role2section['section_id']] = $role2section['crud_permissions'];
							}
						}

						foreach($sections as $section) {

							if(isset($permissions[$section['id']])) {
								$p = [
									'c'=>substr($permissions[$section['id']], 0, 1),
									'r'=>substr($permissions[$section['id']], 1, 1),
									'u'=>substr($permissions[$section['id']], 2, 1),
									'd'=>substr($permissions[$section['id']], 3, 1),
								];
							}
							else {
								$p = ['c'=>0,'r'=>0,'u'=>0,'d'=>0];
							}

							$show_section = true;
							if($crud_permissions) {
								foreach($crud_permissions as $action => $permission) {
									if($permission && !$p[$action]) {
										$show_section = false;
										break;
									}
								}
							}

							if($show_section) {
								$out[$section['section_group_id']]['sections'][] = [
									'id' => $section['id'],
									'name' => $section['name'],
									'uri' => $section['uri'],
									'is_crud_section' => $section['is_crud_section'],
									'c' => $p['c'],
									'r' => $p['r'],
									'u' => $p['u'],
									'd' => $p['d'],
								];
							}
						}
					}
					else {
						foreach($sections as $section) {
							$out[$section['section_group_id']]['sections'][] = [
								'id' => $section['id'],
								'name' => $section['name'],
								'uri' => $section['uri'],
							];
						}
					}
				}
			}
		}

		return $out;
	}

	public function getCmsMenu($role_id=null, $crud_permissions=null) {

		$menu = require(\Yii::getAlias('@admin') . "/config/menu.php");

		foreach($menu as &$item){
			$item['active'] = Yii::$app->controller->id == $item['url'];
			$item['url'] = Url::toRoute($item['url'] . "/index");
		}

		return $menu;
	}
}
