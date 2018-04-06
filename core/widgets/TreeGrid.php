<?php

namespace core\widgets;


class TreeGrid extends \leandrogehlen\treegrid\TreeGrid
{
    public $keyColumnName = 'id';

    public $parentColumnName = 'parent_id';
    /**
     * Renders the data models for the grid view.
     */
    public function renderItems()
    {
        $rows = [];
        $models = array_values($this->dataProvider->getModels());
        $models = $this->normalizeData($models,$this->parentRootValue);
        foreach ($models as $index => $model) {
            $key = $model->id;
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows)) {
            $colspan = count($this->columns);
            return "<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>";
        } else {
            return implode("\n", $rows);
        }
    }
}