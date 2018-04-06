<?php

namespace core\models;


use app\components\ActiveRecord;
use yii\base\Model;
use yii\db\Query;

class ContentBlocksModel extends Model
{
    private $_attributes = [];
    private $_oldAttributes = null;

    public $object_type;
    public $object_id;

    public static function fetch($object_type, $object_id)
    {
        $rows = (new Query())
            ->select(['id', 'key', 'language', 'content'])
            ->from('content_block')
            ->where(['object_type' => $object_type, 'object_id' => $object_id])
            ->all();

        $model = new self([
            'object_type' => $object_type,
            'object_id' => $object_id
        ]);
        foreach ($rows as $row){
            $key = $row['key'] . "_" . $row['language'];
            $model->$key = $row['content'];
            $model->setOldAttribute($key, $row['content']);
        }

        return $model;
    }

    public function setOldAttribute($attribute, $value)
    {
        $this->_oldAttributes[$attribute] = $value;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        foreach ($values as $name => $value) {
            $this->$name = $value;
        }
    }

    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }

    public function getDirtyAttributes()
    {
        $attributes = [];
        if ($this->_oldAttributes === null) {
            foreach ($this->_attributes as $name => $value) {
                $attributes[$name] = $value;
            }
        } else {
            foreach ($this->_attributes as $name => $value) {
                if (!array_key_exists($name, $this->_oldAttributes) || $value !== $this->_oldAttributes[$name]) {
                    $attributes[$name] = $value;
                }
            }
        }
        return $attributes;
    }

    public function save()
    {
        $attributes = $this->getDirtyAttributes();
        foreach ($attributes as $attribute => $value){
            $key = substr($attribute, 0, strlen($attribute) - 3);
            $lang = substr($attribute, -2);
            $condition = [ 'object_type' =>  $this->object_type, 'object_id' => $this->object_id, 'key' => $key, 'language' => $lang];
            if(isset($this->_oldAttributes[$attribute])){
                \Yii::$app->db->createCommand()
                    ->update('content_block', [ 'content' => $value ], $condition)
                    ->execute();
            } else {
                $condition['content'] = $value;
                \Yii::$app->db->createCommand()
                    ->insert('content_block', $condition)
                    ->execute();
            }
            $this->_oldAttributes[$attribute] = $value;
        }
    }

    /**
     * @param ActiveRecord $parentModel
     * @param $attribute
     * @param $value
     * @param $options
     */
    public static function processPostFields( $parentModel, $attribute, $value , $options )
    {
        $postData = \Yii::$app->request->post('ContentBlocksModel');

        $model = self::fetch( $parentModel->tableName(), $parentModel->id );

        $model->setAttributes( $postData );
        $model->save( );
    }

}