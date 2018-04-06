<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events2cashbox".
 *
 * @property integer $id
 * @property string $e_id
 * @property string $c_id
 */
class Events2cashbox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events2cashbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['e_id', 'c_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'e_id' => 'E ID',
            'c_id' => 'C ID',
        ];
    }

    public function getE()
    {
        return $this->hasOne(Events::className(), ['id' => 'e_id']);
    }

    public function getC()
    {
        return $this->hasOne(Cashbox::className(), ['id' => 'c_id']);
    }
}
