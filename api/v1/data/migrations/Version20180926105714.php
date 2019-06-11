<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180926105714 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS  `ox_app` (
		  `id` INT NOT NULL AUTO_INCREMENT,
    		`name` varchar(200) NOT NULL,
			`uuid` varchar(128) NOT NULL,
			`description` TEXT DEFAULT NULL,
			`type` varchar(255) NOT NULL,
			`logo` varchar(255) NULL,
			`category` varchar(255) NOT NULL,
			`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			`date_modified`  DATETIME,
			`created_by` INT(32) NOT NULL DEFAULT '1',
			`modified_by` INT(32) ,
			`isdeleted` BOOLEAN DEFAULT false ,
		  PRIMARY KEY (`id`));");

        $this->addSql("CREATE TABLE IF NOT EXISTS  `ox_app_registry` (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `org_id` INT NOT NULL,
		  `app_id` INT NOT NULL,
		  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `date_modified` DATETIME NULL,
		  PRIMARY KEY (`id`),
		  FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`));
		");

        $this->addSql("ALTER TABLE `ox_privilege` 
			ADD COLUMN `app_id` INT(11) NULL AFTER `org_id`,
			ADD CONSTRAINT FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`)");

        $this->addSql("ALTER TABLE `ox_app` 
			CHANGE COLUMN `uuid` `uuid` VARCHAR(200) NOT NULL");

        $this->addSql("ALTER TABLE `ox_role_privilege` 
			CHANGE COLUMN `org_id` `org_id` INT(32) NULL ,
			ADD COLUMN `app_id` INT(11) NULL AFTER `org_id`,
			ADD CONSTRAINT FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`);
			");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_app");
		$this->addSql("DROP TABLE ox_app_registry");
        $this->addSql("ALTER TABLE `ox_privilege` DROP COLUMN `app_id`");
        $this->addSql("ALTER TABLE `ox_role_privilege` DROP COLUMN `app_id`");

    }
}
