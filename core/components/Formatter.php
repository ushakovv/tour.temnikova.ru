<?php

namespace core\components;


use yii\helpers\Html;

class Formatter extends \yii\i18n\Formatter{

	public function asMediaUrl($value)
	{
		$variant = null;

		$config = \Yii::$app->imageManager->config;
		$tableName = GridView::$lastTableName;
		$attribute = GridView::$lastAttribute;
		$configKey =  $tableName . "." . $attribute;
		if(isset($config[$configKey])){
			$variants = $config[$configKey];
			reset($variants);
			$variant = key($variants);
		}

		if($variant){
			return \Yii::$app->imageManager->getUrl($tableName . "." . $attribute, $variant, $value);
		} else {
			return null;
		}

	}

	public function asMediaImage($value)
	{
		$url = $this->asMediaUrl($value);

		return $url ? Html::img($this->asMediaUrl($value)) : null;
	}
} 