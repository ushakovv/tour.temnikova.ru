<?php

namespace core\components;


use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class DropdownColumn extends DataColumn{

	public $relationModel = null;
	public $valueField = 'id';
	public $titleField = 'name';
	public $filterSort = 'id';

	protected function renderDataCellContent($model, $key, $index)
	{
		$modelClass = $this->relationModel;
		$fullClassName = ClassHelper::getClassPath($modelClass);
		$attribute = $this->attribute;
		$row = $fullClassName::findOne($model->$attribute);
		$titleField = $this->titleField;
		return $row ? $row->$titleField : null;
	}

	protected function renderFilterCellContent()
	{
		$modelClass = $this->relationModel;
		$fullClassName = ClassHelper::getClassPath($modelClass);
		$this->filter = ArrayHelper::map( $fullClassName::find()->orderBy($this->filterSort)->all() , $this->valueField, $this->titleField);
		return parent::renderFilterCellContent();
	}


}