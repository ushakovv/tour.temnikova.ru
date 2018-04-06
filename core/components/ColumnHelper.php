<?php

namespace core\components;


class ColumnHelper {
	private $model = null;

	public function __construct()
	{
		//$this->model = $model;
	}

	public function serial()
	{
		return ['class' => 'yii\grid\SerialColumn'];
	}

	public function int($attribute, $width = '70px')
	{
		return [
			'attribute' => $attribute,
			'options' => [
				'style' => 'width:' . $width
			]
		];
	}

	public function text($attribute)
	{
		return $attribute;
	}

	public function image($attribute)
	{
		return $attribute .':image';
	}

	public function mediaImage($attribute)
	{
		return ($attribute?$attribute:'image_id').':mediaImage';
	}

	public function mediaFile($attribute)
	{
		return [
			'attribute' => $attribute,
			'content' => function($model) use ($attribute){
				return "<a href='" . \Yii::$app->fileManager->getUrl( $model->$attribute ) . "'>{$model->$attribute}</a>";
			},
		];
	}

	public function checkbox($attribute)
	{
		return [
			'attribute' => $attribute,
			'class' => '\core\components\CheckboxColumn'
		];
	}

	public function dropdownByArray($attribute, $items)
	{
		return [
			'attribute' => $attribute,
			'value' => function($model) use ($attribute, $items){
				return isset($items[$model->$attribute]) ? $items[$model->$attribute] : $model->$attribute;
			},
			'filter' => $items
		];
	}


	public function dropdownByModel($attribute, $relationModel, $valueField = 'id', $titleField = 'name', $filterSort = 'id')
	{
		return [
			'attribute' => $attribute,
			'class' => '\core\components\DropdownColumn',
			'relationModel' => $relationModel,
			'valueField' => $valueField,
			'titleField' => $titleField,
			'filterSort' => $filterSort
		];
	}


	public function datetime($attribute)
	{
		return "$attribute:datetime";
	}

    public function lang($attribute = 'lang')
    {
        return [
            'attribute' => $attribute,
            'filter' => ['en' => 'en', 'ru' => 'ru']
        ];
    }

	public function actions($update = true, $delete = true)
	{
		$templateItems = [];
		if($update){
			$templateItems[] = "{update}";
		}
		if($delete){
			$templateItems[] = "{delete}";
		}
		return [
			'class' => 'yii\grid\ActionColumn',
			'template' => "<div style='width:40px'>" . implode("&nbsp;&nbsp;", $templateItems) . "</div>",
			'buttonOptions' => [
				'data-params' => [
					'_csrf' => \Yii::$app->request->getCsrfToken()
				]
			]
		];
	}

} 