<?php

namespace core\components;


use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;

class CheckboxColumn extends DataColumn{

	public $filter = [
		1 => "Да",
		0 => "Нет",
	];

	protected function renderDataCellContent($model, $key, $index)
	{
		$url =  Url::toRoute( \Yii::$app->controller->id . "/update-attribute" );
		$attribute = $this->attribute;
		$value = $model->$attribute;
		$checkedClass = $value ? "checked" : "";
		$id = "cb" . rand(0, 9999);
		$inputHtml = Html::checkbox($id, $value, [
			'id' => $id,
			'onclick' => 'CheckboxColumn.toggle(this, \'' .  $url .'\', \'' . $this->attribute . '\', ' . $model->id . ' )'
		]);
		return Html::tag('div', $inputHtml . "<label for='$id'></label>", [
			'class' => 'checkbox clip-check check-success checkbox-inline',
			
		]);
	}


} 