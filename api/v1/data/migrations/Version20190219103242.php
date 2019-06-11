<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190219103242 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	// $this->addSql("DROP TABLE IF EXISTS ox_app");
        $this->addSql("DROP TRIGGER IF EXISTS before_insert_app");
    	// $this->addSql("CREATE TABLE IF NOT EXISTS `ox_app` (
    	// 	`id` INT NOT NULL AUTO_INCREMENT,
    	// 	`name` varchar(200) NOT NULL,
		// 	`uuid` varchar(128) NOT NULL,
		// 	`description` TEXT DEFAULT NULL,
		// 	`type` varchar(255) NOT NULL,
		// 	`logo` varchar(255) NULL,
		// 	`category` varchar(255) NOT NULL,
		// 	`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
		// 	`date_modified`  DATETIME,
		// 	`created_by` INT(32) NOT NULL DEFAULT '1',
		// 	`modified_by` INT(32) ,
		// 	`isdeleted` BOOLEAN DEFAULT false ,
		// 	PRIMARY KEY (`id`));");
    	$this->addSql("CREATE TABLE IF NOT EXISTS `ox_app_category` (
    		`id` INT NOT NULL AUTO_INCREMENT,
    		`name` varchar(200) NOT NULL,
    		`logo` varchar(255) NOT NULL,
    		`color` varchar(255) NOT NULL,
			`uuid` varchar(128) NOT NULL,
			`description` TEXT DEFAULT NULL,
			`org_id` INT(32) ,
			`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			`date_modified`  DATETIME,
			`created_by` INT(32) NOT NULL DEFAULT '1',
			`modified_by` INT(32) ,
			`isdeleted` BOOLEAN DEFAULT false ,
			PRIMARY KEY (`id`));");
    	$this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_APP',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_APP',15);");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_CATEGORY',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_CATEGORY',15);");
    	$this->addSql("ALTER TABLE `ox_app_registry` ADD `start_options` varchar(255) ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql("DROP TABLE ox_app");
        $this->addSql("DROP TABLE ox_app_category");
        $this->addSql("ALTER TABLE `ox_app_registry` DROP `start_options`");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_APP' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_APP'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_CATEGORY' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_CATEGORY'");
    }
}
