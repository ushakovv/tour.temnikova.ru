<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "social_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $link
 * @property string $logo
 * @property string $logo_active
 * @property integer $status
 * @property integer $sort
 */
class SocialGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'sort'], 'integer'],
            [['name', 'link', 'logo', 'logo_active'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'link' => 'Ссылка',
            'logo' => 'Логотип',
            'logo_active' => 'Логотип (при наведении)',
            'status' => 'Статус',
            'sort' => 'Сортировка',
        ];
    }
}
