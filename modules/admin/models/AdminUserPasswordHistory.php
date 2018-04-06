<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "admin_user_password_history".
 *
 * @property integer $id
 * @property integer $admin_user_id
 * @property string $password
 *
 * @property AdminUser $adminUser
 */
class AdminUserPasswordHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_user_password_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_user_id', 'password'], 'required'],
            [['admin_user_id'], 'integer'],
            [['password'], 'string', 'max' => 128],
            [['admin_user_id', 'password'], 'unique', 'targetAttribute' => ['admin_user_id', 'password'], 'message' => 'The combination of Admin User ID and Password has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_user_id' => 'Admin User ID',
            'password' => 'Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'admin_user_id']);
    }
}
