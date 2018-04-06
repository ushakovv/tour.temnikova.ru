<?php

Yii::import("media.components.transport.LocalTransport");

class TestLocalTransport extends LocalTransport{
	static function getUrl($sPath){
		if($sPath){
			if(substr($sPath,0, 4) == "http"){
				return $sPath;
			} else {
				return  "http://tccc-cm.dev.promo.ru" . $sPath;
			}
		} else {
			return null;
		}
	}
}