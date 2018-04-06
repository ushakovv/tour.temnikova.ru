<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property integer $id
 * @property string $city
 * @property string $date
 * @property string $source
 * @property integer $enabled
 * @property integer $ord
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['enabled', 'ord'], 'integer'],
            [['city', 'source', 'preview'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city' => 'Город',
            'date' => 'Дата',
            'source' => 'Ссылка на видео в vk',
            'preview' => 'Превью',
            'enabled' => 'Показывать',
            'ord' => 'Порядок',
        ];
    }
}
