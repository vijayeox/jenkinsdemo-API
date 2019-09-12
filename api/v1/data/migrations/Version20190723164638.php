<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190723164638 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_activity` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `name` VARCHAR(250) NOT NULL , `app_id` INT(32) NULL, `workflow_id` INT(32) NULL, `created_by` INT(32) NOT NULL DEFAULT '1', `modified_by` INT(32) , `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_modified`  DATETIME , PRIMARY KEY ( `id` ) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_activity_form` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `activity_id`  INT(32) NOT NULL , `form_id` INT(32) NULL, PRIMARY KEY ( `id` )) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_activity_field` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `activity_id` int(64) NOT NULL , `field_id` INT(64) NOT NULL , PRIMARY KEY ( `id` ) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN `workflow_id`;");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN `task_id`;");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN `process_id`;");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN `statuslist`;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_activity");
        $this->addSql("DROP TABLE ox_activity_form");
        $this->addSql("DROP TABLE ox_activity_field");
        $this->addSql("ALTER TABLE `ox_form` ADD `task_id` VARCHAR(128) NULL AFTER `name`;");
        $this->addSql("ALTER TABLE `ox_form` ADD `process_id` VARCHAR(128) NULL AFTER `task_id`;");
        $this->addSql("ALTER TABLE `ox_form` ADD `statuslist` TEXT NULL AFTER `task_id`;");
        $this->addSql("ALTER TABLE `ox_form` ADD `workflow_id` INT(32) NULL;");
    }
}
