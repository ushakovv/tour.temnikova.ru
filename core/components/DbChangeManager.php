<?php

namespace core\components;


use common\models\AdminUser;

class DbChangeManager extends ChangeManager
{
    public $tableName = "change_log";

    protected function _save($row)
    {
        \Yii::$app->db->createCommand()
            ->insert($this->tableName, $row)
            ->execute();
    }
}