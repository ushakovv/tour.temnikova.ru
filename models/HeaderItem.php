<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "header_item".
 *
 * @property integer $id
 * @property integer $id_group
 * @property string $name
 * @property string $title
 * @property string $logo
 * @property string $link
 * @property integer $enabled
 * @property integer $ord
 *
 * @property HeaderGroup $idGroup
 */
class HeaderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'header_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_group', 'enabled', 'ord'], 'integer'],
            [['name', 'title', 'logo', 'link'], 'string', 'max' => 150],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => HeaderGroup::className(), 'targetAttribute' => ['id_group' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_group' => 'Группа',
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
    public function getIdGroup()
    {
        return $this->hasOne(HeaderGroup::className(), ['id' => 'id_group']);
    }
}
