<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190109101830 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE `ox_workflow` ( `id` INT(32) NOT NULL AUTO_INCREMENT , `name` INT(32) NOT NULL , `process_ids` TEXT NOT NULL , `app_id` INT(11) NOT NULL , `form_id` TEXT NOT NULL , `process_keys` TEXT NOT NULL , `org_id` int(11) NOT NULL, `created_by` INT(32) NOT NULL DEFAULT '1', `modified_by` INT(32) , `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			`date_modified`  DATETIME, PRIMARY KEY (`id`),FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`)) ENGINE = InnoDB;");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_WORKFLOW',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_WORKFLOW',15);");
        $this->addSql("ALTER TABLE `ox_form` ADD `task_id` VARCHAR(128) NULL AFTER `name`;");
        $this->addSql("ALTER TABLE `ox_form` ADD `process_id` VARCHAR(128) NULL AFTER `task_id`;");
        $this->addSql("ALTER TABLE `ox_field` ADD `constraints` TEXT NULL AFTER `options`;");
        $this->addSql("ALTER TABLE `ox_field` ADD `properties` TEXT NULL AFTER `constraints`;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_workflow`;");
        $this->addSql("ALTER TABLE `ox_field` DROP `constraints`;");
        $this->addSql("ALTER TABLE `ox_field` DROP `properties`;");
        $this->addSql("ALTER TABLE `ox_form` DROP `process_id`;");
    }
}
