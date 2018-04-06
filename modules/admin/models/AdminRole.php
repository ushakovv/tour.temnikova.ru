<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "admin_role".
 *
 * @property integer $id
 * @property string $name
 *
 * @property AdminUser[] $adminUsers
 */
class AdminRole extends \yii\db\ActiveRecord
{
	const FULL_ACCESS = 1000;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Роль',
	        'permissions' => 'Права',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUsers()
    {
        return $this->hasMany(AdminUser::className(), ['role_id' => 'id']);
    }
}
