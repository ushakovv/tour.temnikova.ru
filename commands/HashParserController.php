<?php

namespace app\commands;

use app\components\HashParser;

class HashParserController extends \yii\console\Controller{
	public function actionIndex()
	{
		//date_default_timezone_set('Europe/Moscow');

		$hp = new HashParser();
		$hp->process();

		//$hp->fillInstagramFriends();
	}
}