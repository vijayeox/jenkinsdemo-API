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
		  `name` VARCHAR(200) NOT NULL COMMENT '	',
		  `uuid` varchar(20) NOT NULL,
		  `description` LONGTEXT NOT NULL,
		  `type` VARCHAR(200) NOT NULL,
		  `logo` VARCHAR(200) NULL,
		  `date_created` DATETIME NOT NULL,
		  `date_modified` DATETIME NOT NULL,
		  PRIMARY KEY (`id`));");

        $this->addSql("CREATE TABLE IF NOT EXISTS  `ox_app_registry` (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `org_id` INT NOT NULL,
		  `app_id` INT NOT NULL,
		  `date_created` DATETIME NOT NULL,
		  `date_modified` DATETIME NOT NULL,
		  PRIMARY KEY (`id`));
		");

        $this->addSql("ALTER TABLE `ox_privilege` 
			ADD COLUMN `app_id` INT(32) NULL AFTER `org_id`");

        $this->addSql("ALTER TABLE `ox_app` 
			CHANGE COLUMN `uuid` `uuid` VARCHAR(200) NOT NULL");

        $this->addSql("ALTER TABLE `ox_role_privilege` 
			CHANGE COLUMN `org_id` `org_id` INT(32) NULL ,
			ADD COLUMN `app_id` INT(32) NULL AFTER `org_id`;
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
