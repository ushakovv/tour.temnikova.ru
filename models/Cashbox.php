<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cashbox".
 *
 * @property integer $id
 * @property string $name
 * @property integer $logo
 * @property string $link
 * @property string $phone
 *
 * @property Event2cashbox[] $event2cashboxes
 */
class Cashbox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'link', 'logo', 'front_name'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 20],
            [['order'], 'integer'],
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
            'front_name' => 'Имя для фронта',
            'logo' => 'Логотип',
            'link' => 'Ссылка',
            'phone' => 'Телефон',
            'order' => 'Сортировка'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent2cashboxes()
    {
        return $this->hasMany(Event2cashbox::className(), ['c_id' => 'id']);
    }
}
