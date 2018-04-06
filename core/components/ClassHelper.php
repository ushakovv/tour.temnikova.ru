<?php

namespace core\components;


class ClassHelper {

	public static function getClassPath($className)
	{
		$fullClassName = null;
		if(class_exists($className)){
			$fullClassName = $className;
		}
		if(!$fullClassName){
			if(class_exists("\\admin\\models\\" . $className)){
				$fullClassName = "\\admin\\models\\" . $className;
			} else {
				if(class_exists("\\core\\models\\" . $className)){
					$fullClassName = "\\core\\models\\" . $className;
				} else {
					if(class_exists("\\app\\models\\" . $className)){
						$fullClassName = "\\app\\models\\" . $className;
					}
				}
			}
		}
		if(!$fullClassName){
			$fullClassName = $className;
		}
		return $fullClassName;
	}
} 