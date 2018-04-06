<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "footer_group".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 */
class FooterGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'footer_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'string'],
            [['name'], 'string', 'max' => 150],
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
            'code' => 'Код',
        ];
    }
}
