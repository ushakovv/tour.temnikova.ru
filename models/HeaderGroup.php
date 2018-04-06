<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "header_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $logo
 * @property string $link
 * @property integer $enabled
 * @property integer $ord
 *
 * @property HeaderItem[] $headerItems
 */
class HeaderGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'header_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled', 'ord'], 'integer'],
            [['name', 'title', 'logo', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'title' => 'Title',
            'logo' => 'Логотип',
            'link' => 'Ссылка',
            'enabled' => 'Активно',
            'ord' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeaderItems()
    {
        return $this->hasMany(HeaderItem::className(), ['id_group' => 'id']);
    }
}
