<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "partner".
 *
 * @property integer $id
 * @property string $title
 * @property string $logo
 * @property string $link
 * @property integer $enabled
 * @property integer $ord
 */
class Partner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled', 'ord'], 'integer'],
            [['title', 'link', 'logo'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Подпись',
            'logo' => 'Лого',
            'link' => 'Ссылка',
            'enabled' => 'Показывать',
            'ord' => 'Порядок',
        ];
    }
}
