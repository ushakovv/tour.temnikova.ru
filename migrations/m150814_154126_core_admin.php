<?php

use yii\db\Schema;
use yii\db\Migration;

class m150814_154126_core_admin extends Migration
{
	public function up()
	{
		$this->execute("
			CREATE TABLE IF NOT EXISTS `admin_role` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(128) NOT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;
		");

		$this->execute("
			CREATE TABLE IF NOT EXISTS `admin_section_group` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL,
				`ord` TINYINT(3) NULL DEFAULT '0',
				`enabled` TINYINT(3) NULL DEFAULT '1',
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			AUTO_INCREMENT=2;
		");

	    $this->execute("
	        CREATE TABLE IF NOT EXISTS `admin_section` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`section_group_id` INT(11) NULL DEFAULT NULL,
				`name` VARCHAR(255) NOT NULL,
				`uri` VARCHAR(255) NOT NULL,
				`ord` TINYINT(3) NULL DEFAULT '0',
				`enabled` TINYINT(3) NULL DEFAULT '1',
				`is_crud_section` TINYINT(3) NULL DEFAULT '1',
				PRIMARY KEY (`id`),
				INDEX `FK_admin_section_admin_section_group` (`section_group_id`),
				CONSTRAINT `FK_admin_section_admin_section_group` FOREIGN KEY (`section_group_id`) REFERENCES `admin_section_group` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
	    ");

		$this->execute("
			CREATE TABLE `admin_role2section` (
				`role_id` INT(11) NOT NULL,
				`section_id` INT(11) NOT NULL,
				`crud_permissions` VARCHAR(4) NOT NULL DEFAULT '0000',
				PRIMARY KEY (`role_id`, `section_id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;
		");

		$this->execute("
			CREATE TABLE `admin_user` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`username` VARCHAR(256) NULL DEFAULT NULL,
				`password` VARCHAR(128) NULL DEFAULT NULL,
				`salt` VARCHAR(64) NULL DEFAULT NULL,
				`description` VARCHAR(128) NULL DEFAULT NULL,
				`count_login_errors` INT(11) NULL DEFAULT '0',
				`dt_change_password` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
				`password_hash` VARCHAR(255) NULL DEFAULT NULL,
				`auth_key` VARCHAR(255) NULL DEFAULT NULL,
				`role_id` INT(11) NULL DEFAULT NULL,
				`email` VARCHAR(255) NULL DEFAULT NULL,
				`is_get_notice` TINYINT(4) NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT;
		");

		$this->execute("
			CREATE TABLE `admin_user_password_history` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`admin_user_id` INT(11) NOT NULL,
				`password` VARCHAR(128) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `index_2` (`admin_user_id`, `password`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT;
		");

	}

	public function down()
	{
		echo "m150814_154126_core_admin cannot be reverted.\n";

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
