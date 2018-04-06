<?php

namespace core\media\transport;

class LocalTransport extends \yii\base\Component{

	public $staticUrl = '/static';

	static function preparePath($sPath){
		if(!file_exists($sPath)){
			@mkdir($sPath, 0777, true);
			@chmod($sPath, 0777);
		}
		if(!file_exists($sPath)){
			throw new \yii\base\Exception("Не удалось создать каталог, проверьте права: " . $sPath);
		}
	}

	static function load($sPath)
	{
		$sRealPath = self::getFullPath($sPath);
		if(file_exists($sRealPath)){
			return file_get_contents($sRealPath);
		} else {
			return null;
		}
	}

	static function save($sPath, $sContent){
		$sRealPath = self::getFullPath($sPath);
		self::preparePath(dirname($sRealPath));
		file_put_contents($sRealPath, $sContent );
		chmod($sRealPath, 0777);
	}

	static function getFullPath($sPath){
		if(strpos($sPath, '@') === false){
			return \Yii::getAlias('@static') . $sPath;
		} else {
			return \Yii::getAlias($sPath);
		}

	}

	static function exists($sPath){
		return (file_exists(self::getFullPath($sPath)));
	}

	static function filesize($sPath){
		return filesize(self::getFullPath($sPath));
	}

	static function delete($sPath){
		$sRealPath = self::getFullPath($sPath);
		if(file_exists($sRealPath)){
			unlink($sRealPath);
		}
	}

	public function getUrl($sPath){
		if($sPath){
			if(substr($sPath,0, 4) == "http"){
				return $sPath;
			} else {
				//$protocol = \Yii::$app->request->isSecureConnection ? "https" : "http";
				//return  $protocol . "://".$_SERVER["HTTP_HOST"].$sPath;
				return $this->staticUrl . $sPath;
			}
		} else {
			return null;
		}
	}
}