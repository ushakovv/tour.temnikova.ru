<?php

use yii\db\Schema;
use yii\db\Migration;

class m150814_154251_core_media extends Migration
{
    public function up()
    {
	    $this->execute("
	        CREATE TABLE IF NOT EXISTS `media_gallery` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`object_type` VARCHAR(128) NULL DEFAULT NULL,
				`object_id` INT(11) NULL DEFAULT NULL,
				`media_image_id` INT(11) NULL DEFAULT NULL,
				`enabled` TINYINT(4) NULL DEFAULT '1',
				`ord` INT(11) NULL DEFAULT '1000',
				PRIMARY KEY (`id`),
				INDEX `FK_media_gallery_media_image` (`media_image_id`),
				INDEX `object_type` (`object_type`, `object_id`, `enabled`, `ord`),
				CONSTRAINT `FK_media_gallery_media_image` FOREIGN KEY (`media_image_id`) REFERENCES `media_image` (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			;
	    ");

	    $this->execute("
	        CREATE TABLE `media_image` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`type` VARCHAR(32) NOT NULL,
				`src` VARCHAR(128) NULL DEFAULT NULL,
				`processed` TINYINT(4) NULL DEFAULT '0',
				`gallery_id` INT(11) NULL DEFAULT NULL,
				PRIMARY KEY (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB
			ROW_FORMAT=COMPACT
			AUTO_INCREMENT=21
			;
	    ");

    }

    public function down()
    {
        echo "m150814_154251_core_media cannot be reverted.\n";

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
