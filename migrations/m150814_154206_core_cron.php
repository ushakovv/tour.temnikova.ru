<?php

use yii\db\Schema;
use yii\db\Migration;

class m150814_154206_core_cron extends Migration
{
    public function up()
    {
		$this->execute("
			CREATE TABLE `cron` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL,
				`object` VARCHAR(128) NOT NULL,
				`hours` VARCHAR(128) NULL DEFAULT NULL,
				`enabled` TINYINT(4) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			;
		");

	    $this->execute("
	        CREATE TABLE `cron_log` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`dt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`cron_id` INT(11) NULL DEFAULT NULL,
				`success` TINYINT(4) NOT NULL DEFAULT '1',
				`note` TEXT NULL,
				PRIMARY KEY (`id`),
				INDEX `FK_cron_log_cron` (`cron_id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			;
	    ");
    }

    public function down()
    {
        echo "m150814_154206_core_cron cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
