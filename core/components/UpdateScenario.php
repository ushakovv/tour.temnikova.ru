<?php

namespace core\components;


use yii\helpers\Json;

class UpdateScenario {

	static private $_scenario = [];

	public static function registerHandler($attribute, $class, $method, $options = [], $afterSave = false){
		self::$_scenario[] = [
			'attribute' => $attribute,
			'class' => $class,
			'method' => $method,
			'options' => $options,
			'afterSave' => $afterSave
		];
	}

	public static function serialize()
	{
		return Json::encode(self::$_scenario);
	}

	public static function executeFromPost($model, $attributes, $field = 'updateScenario', $afterSave = false)
	{
		$data = \Yii::$app->request->post($field);
		if($data){
			$handlers = Json::decode($data, true);
			foreach($handlers as $handler){
				if($handler['afterSave'] == $afterSave){
					$attribute = $handler['attribute'];
					$className = $handler['class'];
					$method = $handler['method'];
					$options = isset($handler['options']) ? $handler['options'] : [];
					$value = isset($attributes[$attribute]) ? $attributes[$attribute] : null;
						$result = $className::$method( $model, $attribute, $value , $options );
					if($result !== null){
						$attributes[$attribute] = $result;
					} else {
						unset($attributes[$attribute]);
					}
				}
			}
		}
		return $attributes;
	}

} 