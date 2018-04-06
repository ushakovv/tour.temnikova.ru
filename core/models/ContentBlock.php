<?php

namespace core\models;

use Yii;

/**
 * This is the model class for table "content_block".
 *
 * @property integer $id
 * @property string $key
 * @property string $language
 * @property string $name
 * @property string $content
 * @property string $dt_modification
 * @property integer $api_export
 * @property string $type
 * @property integer $group_id
 * @property integer $precache
 */
class ContentBlock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['dt_modification'], 'safe'],
            [['api_export', 'group_id', 'precache'], 'integer'],
            [['group_id'], 'required'],
            [['key'], 'string', 'max' => 64],
            [['language'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 128],
            [['type'], 'string', 'max' => 255],
            [['key', 'language'], 'unique', 'targetAttribute' => ['key', 'language'], 'message' => 'The combination of Key and Language has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'language' => 'Language',
            'name' => 'Name',
            'content' => 'Content',
            'dt_modification' => 'Dt Modification',
            'api_export' => 'Api Export',
            'type' => 'Type',
            'group_id' => 'Group ID',
            'precache' => 'Precache',
        ];
    }
}
