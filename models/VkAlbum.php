<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vk_album".
 *
 * @property string $id
 * @property string $name
 * @property string $album_id
 */
class VkAlbum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vk_album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'album_id'], 'string', 'max' => 255],
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
            'album_id' => 'ID Альбома',
        ];
    }
}
