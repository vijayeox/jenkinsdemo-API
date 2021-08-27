<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414082217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
              $this->addSql("ALTER TABLE `ox_file_audit_log` ADD COLUMN `assoc_id` INT NULL AFTER `uuid`");
              $this->addSql("ALTER TABLE ox_file_audit_log ADD COLUMN `entity_id` INT(32) NULL ");
              $this->addSql("ALTER TABLE `ox_file_audit_log` ADD COLUMN `is_active` int(2) NOT NULL DEFAULT '1' AFTER `assoc_id`;");
              $this->addSql("ALTER TABLE `ox_file_audit_log` DROP COLUMN `workflow_instance_id`");
              $this->addSql("ALTER TABLE `ox_file_audit_log` DROP COLUMN `server_info`");
              $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `workflow_instance_id`");
              $this->addSql("ALTER TABLE `ox_file` DROP  FOREIGN KEY `FK_FileParentId`;");
              $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `parent_id`");              
              $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
              $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
              $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");
              $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`) VALUES (new.`id`, 'create', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`,new.`assoc_id`,new.`is_active`,new.`entity_id`);");
              $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`) VALUES (new.`id`, 'update', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`,new.`assoc_id`,new.`is_active`,new.`entity_id`);");
              $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`) VALUES (old.`id`, 'delete', old.`uuid`, old.`org_id`, old.`form_id`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`,old.`assoc_id`,old.`is_active`,old.`entity_id`);");
              $this->addSql("DELETE FROM `ox_file` WHERE latest=0"); 
              $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `latest`");

              $this->addSql("ALTER TABLE ox_workflow_instance MODIFY file_id INT(32) NOT NULL");

    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_file_audit_log` drop COLUMN `assoc_id`;");
              $this->addSql("ALTER TABLE ox_file_audit_log drop COLUMN `entity_id`;");
              $this->addSql("ALTER TABLE `ox_file_audit_log` drop COLUMN `is_active`;");
        // this down() migration is auto-generated, please modify it to your needs

    }
}
