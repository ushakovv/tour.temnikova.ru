<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sites".
 *
 * @property integer $Id
 * @property string $name
 * @property string $url
 * @property string $logo
 * @property integer $active
 * @property integer $sort
 */
class Sites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'sort'], 'integer'],
            [['name', 'url', 'logo'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'name' => 'Имя',
            'url' => 'URL адрес сайта',
            'logo' => 'Логотип',
            'active' => 'Статус',
            'sort' => 'Порядок сортировки',
        ];
    }
}
