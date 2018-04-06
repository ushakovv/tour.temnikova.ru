<?php

namespace core\components;


use admin\models\AdminUser;
use yii\base\Component;

abstract class ChangeManager extends Component
{
    /**
     * @param string $table
     * @param int $row_id
     * @param string $attribute
     * @param string $value_old
     * @param string $value_new
     */
    public function add($table, $row_id, $attribute, $value_old, $value_new)
    {
        if(is_array($value_old)){
            $value_old = implode(",", $value_old);
        }
        if(is_array($value_new)){
            $value_new = implode(",", $value_new);
        }
        if($value_old == $value_new){
            return false;
        }
        $row = [
            'table' => $table,
            'attribute' => $attribute,
            'row_id' => $row_id,
            'value_old' => $value_old,
            'value_new' => $value_new
        ];
        $user = \Yii::$app->user->identity;
        if($user instanceof AdminUser){
            $row['admin_user_id'] = $user->id;
        }
        $this->_save($row);
        return true;
    }
    protected function _save($row)
    {
        // overrdide this
    }
}