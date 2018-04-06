<?php

namespace app\models;

use Yii;
use app\models\Events;
use app\components\validators\PhoneValidator;

/**
 * This is the model class for table "tickets".
 *
 * @property string $id
 * @property string $e_id
 * @property string $email
 * @property string $phone
 * @property string $name
 * @property string $dt
 */
class Tickets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['e_id', 'integer'],
            ['dt', 'safe'],
            [['phone', 'name', 'email'], 'required'],
            ['email', 'email'],
            ['phone', PhoneValidator::className(), 'message' => 'Поле заполненно не корректно'],
            [['phone', 'name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'e_id' => 'Концерт',
            'email' => 'Email',
            'phone' => 'Телефон',
            'name' => 'Имя',
            'dt' => 'Дата',
        ];
    }

    public function getEvents()
    {
        return $this->hasOne(Events::className(), ['id' => 'e_id']);
    }
}
