<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media_image".
 *
 * @property integer $id
 * @property string $type
 * @property string $src
 * @property integer $processed
 */
class MediaImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['processed'], 'integer'],
            [['type'], 'string', 'max' => 32],
            [['src'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'src' => 'Src',
            'processed' => 'Processed',
        ];
    }
}
