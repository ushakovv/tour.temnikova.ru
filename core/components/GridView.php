<?php

namespace core\components;


use yii\helpers\Html;

class GridView extends \yii\grid\GridView {

	public $tableOptions = ['class' => 'table table-striped table-hover'];

	public static $lastTableName = null;
	public static $lastAttribute = null;
	/**
	 * Renders a table row with the given data model and key.
	 * @param mixed $model the data model to be rendered
	 * @param mixed $key the key associated with the data model
	 * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
	 * @return string the rendering result
	 */
	public function renderTableRow($model, $key, $index)
	{
		$cells = [];
		self::$lastTableName = $model->tableName();
		/** @var Column $column */
		foreach ($this->columns as $column) {
			self::$lastAttribute = ($column instanceof \yii\grid\DataColumn) ? $column->attribute : null;
			$cells[] = $column->renderDataCell($model, $key, $index);
		}
		if ($this->rowOptions instanceof Closure) {
			$options = call_user_func($this->rowOptions, $model, $key, $index, $this);
		} else {
			$options = $this->rowOptions;
		}
		$options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

		return Html::tag('tr', implode('', $cells), $options);
	}

} 