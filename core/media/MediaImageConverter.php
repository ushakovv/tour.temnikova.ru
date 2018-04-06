<?php


class MediaImageConverter {
	public static function processInput($name, $default, $options = array()){
		$tmp_file = null;
		if(strpos($name, "[") > 0){
			$matches = array();
			preg_match('/(.+?)\[(.+?)\]/', $name, $matches);
			$tmp_file = isset($_FILES[$matches[1]]) && isset($_FILES[$matches[1]]['tmp_name'][$matches[2]]) ? $_FILES[$matches[1]]['tmp_name'][$matches[2]] : null;
		}
		if($tmp_file){
			$content = file_get_contents($tmp_file);
			$image = Yii::app()->imageManager->put($options['type'] , $content);
			return $image['id'];
		} else {
			return $default;
		}
	}
} 