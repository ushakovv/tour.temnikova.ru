<?php

namespace core\media;

trait ImageTrait {

	public function getImageSrc($attribute, $variant = null)
	{
		$tableName = str_replace(["{", "%", "}"], "", $this->tableName());
		return \Yii::$app->imageManager->getUrl($tableName . "." . $attribute, $variant, $this->$attribute);

		if(!$variant) {
			$config = \Yii::$app->imageManager->config;
			$configKey = $tableName . "." . $attribute;
			if(isset($config[$configKey])){
				$variants = $config[$configKey];
				reset($variants);
				$variant = key($variants);
			}
		}

		return \Yii::$app->imageManager->getUrl($tableName . "." . $attribute, $variant, $this->$attribute);
	}

	public function getImageHtml($attribute, $variant = null)
	{
		return "<img src='{$this->getImageSrc($attribute, $variant)}' />";
	}

	protected function getShortClassName()
	{
		$path = explode('\\', __CLASS__);
		return array_pop($path);
	}
}