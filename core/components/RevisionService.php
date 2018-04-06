<?php

namespace core\components;


use yii\base\Exception;

class RevisionService {

	protected static function _getRevFile()
	{
		return \Yii::getAlias('@runtime/revision');
	}

	protected static function _updateSymlink($rev)
	{
		$targetFolder = \Yii::getAlias('@webroot') . '/f';
		$slFolder = \Yii::getAlias('@webroot/f-v');
		if(!file_exists($slFolder)){
			throw new Exception("Please create and make writable: " . $slFolder);
		}
		$files = glob($slFolder . "/*");
		foreach($files as $file){
			if(is_link($file))
				@unlink($file);
		}
		symlink( $targetFolder, $slFolder . '/' . $rev );
		if (!file_exists($slFolder . '/' . $rev)){
			echo "\nWARNING! Symlink error\n";
		};
	}

	public static function generateRev()
	{
		return rand(100000, 999999);
	}

	public static function getRev()
	{
		$rev = "";
		$file = self::_getRevFile();
		if(file_exists($file)){
			$rev = file_get_contents($file);
		}
		if(!$rev){
			$rev = self::updateRev();
		}
		return $rev;
	}

	public static function setRev($rev)
	{
		$file = self::_getRevFile();
		file_put_contents($file, $rev);
		self::_updateSymlink($rev);
	}

	public static function updateRev()
	{
		$rev = self::generateRev();
		self::setRev($rev);
		return $rev;
	}
}