<?php

use yii\db\Schema;
use yii\db\Migration;

class m150814_154154_core_content_block extends Migration
{
    public function up()
    {
		$this->execute("
			CREATE TABLE `content_block_group` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(128) NOT NULL,
				`order` INT(3) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT;
		");

	    $this->execute("
	        CREATE TABLE `content_block` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`key` VARCHAR(64) NULL DEFAULT NULL,
				`language` VARCHAR(2) NULL DEFAULT 'ru',
				`name` VARCHAR(128) NULL DEFAULT NULL,
				`content` TEXT NULL,
				`dt_modification` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`api_export` TINYINT(4) NULL DEFAULT '0',
				`type` VARCHAR(255) NULL DEFAULT 'textarea',
				`group_id` INT(11) NOT NULL,
				`precache` TINYINT(4) NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				UNIQUE INDEX `key` (`key`, `language`),
				INDEX `precache` (`precache`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT;
	    ");
    }

    public function down()
    {
        echo "m150814_154154_core_content_block cannot be reverted.\n";

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
