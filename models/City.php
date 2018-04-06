<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property string $name
 * @property integer $coords_x
 * @property integer $coords_y
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coords_x', 'coords_y', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Город',
            'coords_x' => 'Координаты X',
            'coords_y' => 'Координаты Y',
            'status' => 'Публиковать'
        ];
    }

    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['city_id' => 'id']);
    }
}
