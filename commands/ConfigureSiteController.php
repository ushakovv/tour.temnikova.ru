<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;


class ConfigureSiteController extends Controller
{

	private function _checkConnection($dbHost, $dbPort, $dbName, $dbLogin, $dbPassword)
	{
		try{
			$dbh = new \PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbLogin, $dbPassword);
			return true;
		} catch (\PDOException $e){
			return $e->getMessage();
		}
	}

	public function actionIndex()
	{
		top:
		echo "*** Database configuration ***\n";
		$dbHost = $this->prompt("DB host:", ['default' => 'localhost']);
		$dbPort = $this->prompt("DB port:", ['default' => 3306]);
		$dbName = $this->prompt("DB name:");
		$dbLogin = $this->prompt("DB login:");
		$dbPassword = $this->prompt("DB password:");

		echo "Check connection...";
		$checkConnection = $this->_checkConnection($dbHost, $dbPort, $dbName, $dbLogin, $dbPassword);
		if($checkConnection === true){
			echo "Ok\n";
		} else {
			echo "Error: " . $checkConnection . "\n";
			$wantToContinue = Console::confirm('Do you want to save this DB config?');
			if(!$wantToContinue){
				goto top;
			}
		}

		$localPhpPath = "config/local.php";

		$localPhp = file_get_contents($localPhpPath);
		$localPhp = str_replace(
			['{HOST}', '{PORT}', '{DBNAME}', '{USERNAME}', '{PASSWORD}'],
			[$dbHost, $dbPort, $dbName, $dbLogin, $dbPassword],
			$localPhp
		);

		file_put_contents($localPhpPath, $localPhp);

		echo "File config/local.php is saved\n";
	}
}
