<?php

namespace core\components;

use yii\base\Exception;
use yii\db\Query;
use yii\helpers\VarDumper;

class Many2ManyHelper
{

    public static function updateLink($model, $attribute, $value, $options = [])
    {
        $pivotTable = $options['pivotTable'];
        $valueFrom = $model->id;

        $pivotFieldFrom = $options['pivotFieldFrom'];
        $pivotFieldTo = $options['pivotFieldTo'];

        $oldIds = (new Query())
            ->select([$pivotFieldTo])
            ->from($pivotTable)
            ->where([$pivotFieldFrom => $valueFrom])
            ->column();

        if ($valueFrom) {
            \Yii::$app->db->createCommand()->delete($pivotTable, [$pivotFieldFrom => $valueFrom])->execute();
        }

        $ids = [];
        if ($value) {
            if (is_string($value)) {
                $value = explode(",", $value);
            }

            $insertPairs = [];
            foreach ($value as $id) {
                $insertPairs[] = [$valueFrom, $id];
                $ids[] = $id;
            }

            \Yii::$app->db->createCommand()->batchInsert(
                $pivotTable,
                [$pivotFieldFrom, $pivotFieldTo],
                $insertPairs
            )->execute();
        }

        //\Yii::$app->changeManager->add($model->tableName(), $model->id, $pivotTable, $oldIds, $ids);

    }

    public static function updateTags($model, $attribute, $value, $options = [])
    {
        $tagModel = $options['tagModel'];

        if(!is_array($value)){
            $value = explode(",", $value);
        }

        $tagIds = [];
        foreach ($value as $tag) {
            $tag = trim($tag);
            $tagRow = $tagModel::findOne(['name' => $tag]);
            if (!$tagRow) {
                $tagRow = new $tagModel;
                $tagRow->name = $tag;
                $tagRow->save();
            }
            $tagIds[] = $tagRow->id;
        }

        return self::updateLink($model, $attribute, $tagIds, $options);
    }

    public static function updateCollection($model, $attribute, $value, $options = [])
    {
        $recordType = $options['record_type'];
        $recordId = $model->id;

        $oldRows = (new Query())
            ->select(['record2_type', 'record2_id'])
            ->from('record_link')
            ->where([ 'record1_type' => $recordType, 'record1_id' => $recordId ])
            ->all();
        $oldRowsIds = array_map(
            function($row){ return $row['record2_type'] . "-" . $row['record2_id']; },
            $oldRows
        );
        
        if ($recordType && $recordId) {
            \Yii::$app->db->createCommand()->delete(
                'record_link',
                [ 'record1_type' => $recordType, 'record1_id' => $recordId ]
            )->execute();
        }

        $newRowsIds = [];
        if ($value) {
            if (is_string($value)) {
                $value = explode(",", $value);
            }

            $insertPairs = [];
            foreach ($value as $id) {

                list($modelClass, $id) = explode(":", $id);
                $type = $modelClass::tableName();

                $insertPairs[] = [$recordType, $recordId, $type, $id];
                $newRowsIds[] = $type . "-" . $id;
            }

            \Yii::$app->db->createCommand()->batchInsert(
                'record_link',
                ['record1_type', 'record1_id', 'record2_type', 'record2_id'],
                $insertPairs
            )->execute();
            
            \Yii::$app->changeManager->add($model->tableName(), $model->id, 'record_link', $oldRowsIds, $newRowsIds);

            return null;
        }
    }
} 