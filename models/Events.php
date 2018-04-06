<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property string $tour_id
 * @property string $city
 * @property string $tour_desc
 * @property string $tour_dt
 * @property string $image
 * @property double $map_x
 * @property double $map_y
 * @property string $vk_event
 * @property integer $was
 * @property integer $status
 * @property integer $sort
 * @property string $p_alias
 * @property string $p_dt
 * @property string $p_time
 * @property string $p_auth
 * @property integer $city_id
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tour_id', 'was', 'status', 'sort', 'city_id', 'api_id'], 'integer'],
            [['map_x', 'map_y'], 'number'],
            [['p_dt', 'p_time'], 'safe'],
            [['tour_desc', 'image', 'vk_event', 'p_alias', 'p_auth', 'address'], 'string', 'max' => 255],
            [['tour_dt', 'place', 'album_id'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tour_id' => 'ID Тура',
            'tour_desc' => 'Описание концерта',
            'tour_dt' => 'Дата',
            'image' => 'Image',
            'map_x' => 'Координаты на карте - X',
            'map_y' => 'Координаты на карте - Y',
            'vk_event' => 'Vk Event',
            'was' => 'Концерт прошел?',
            'status' => 'Статус',
            'sort' => 'Сортировка',
            'p_alias' => 'Ponaminalu - псевдоним',
            'p_dt' => 'Ponaminalu - дата',
            'p_time' => 'Ponaminalu - время',
            'p_auth' => 'Ponaminalu - авторизация',
            'city_id' => 'Город',
            'place' => 'Место',
            'address' => 'Адрес',
            'album_id' => 'VK Альбом',
            'name' => 'Город',
        ];
    }

    public function getEvents2cashbox()
    {
        return $this->hasMany(Events2cashbox::className(), ['e_id' => 'id'])->viaTable(Cashbox::className(), ['id' => 'c_id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id', 'city_id']);
    }
}
