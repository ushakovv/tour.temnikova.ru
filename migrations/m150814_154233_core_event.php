<?php

use yii\db\Schema;
use yii\db\Migration;

class m150814_154233_core_event extends Migration
{
    public function up()
    {
	    $this->execute("
	    CREATE TABLE `event_counter` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`date` DATE NULL DEFAULT NULL,
			`event` VARCHAR(64) NULL DEFAULT NULL,
			`cnt` INT(11) NULL DEFAULT '0',
			PRIMARY KEY (`id`),
			UNIQUE INDEX `date` (`date`, `event`)
		)
		COLLATE='utf8_general_ci'
		ENGINE=InnoDB
		ROW_FORMAT=COMPACT;
	    ");

	    $this->execute("
	        CREATE TABLE `event_group` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NULL DEFAULT NULL,
				`show` TINYINT(3) NULL DEFAULT '1',
				`ord` INT(11) NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			;
	    ");

	    $this->execute("
	        CREATE TABLE `event_name` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`key` VARCHAR(255) NULL DEFAULT NULL,
				`name` VARCHAR(255) NULL DEFAULT NULL,
				`show` TINYINT(3) NULL DEFAULT '1',
				`group_id` INT(11) NULL DEFAULT '0',
				`ord` INT(11) NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			;
	    ");
    }

    public function down()
    {
        echo "m150814_154233_core_event cannot be reverted.\n";

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
