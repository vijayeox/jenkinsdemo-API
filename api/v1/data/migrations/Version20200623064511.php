<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623064511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Changes to Entity Table
        $this->addSql("ALTER TABLE `ox_app_entity` ADD COLUMN `start_date_field` varchar(1024) NULL AFTER `uuid`");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD COLUMN `end_date_field` varchar(1024) NULL AFTER `start_date_field`");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD COLUMN `status_field` varchar(1024) NULL AFTER `end_date_field`");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD INDEX entityStartDate (`start_date_field`)");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD INDEX entityEndDate (`end_date_field`)");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD INDEX entityStatus (`status_field`)");

        // Changes to File Table
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `start_date` DateTime NULL AFTER `last_workflow_instance_id`");
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `end_date` DateTime NULL AFTER `start_date`");
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `status` varchar(255) NULL AFTER `end_date`");
        $this->addSql("ALTER TABLE `ox_file` ADD version INTEGER NOT NULL DEFAULT 1");
        $this->addSql("ALTER TABLE `ox_file` ADD INDEX fileStartDate (`start_date`)");
        $this->addSql("ALTER TABLE `ox_file` ADD INDEX fileEndDate (`end_date`)");
        $this->addSql("ALTER TABLE `ox_file` ADD INDEX fileStatus (`status`)");
        $this->addSql("ALTER TABLE `ox_file` ADD INDEX fileVersion (`version`)");

        // Changes to File Audit Log Table and Creation of triggers
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");
        $this->addSql("ALTER TABLE ox_file_audit_log ADD COLUMN `start_date` DateTime NULL AFTER `last_workflow_instance_id`");
        $this->addSql("ALTER TABLE `ox_file_audit_log` ADD COLUMN `end_date` DateTime NULL AFTER `start_date`");
        $this->addSql("ALTER TABLE `ox_file_audit_log` ADD COLUMN `status` varchar(255) NULL AFTER `end_date`");
        $this->addSql("ALTER TABLE `ox_file_audit_log` ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_file_audit_log` ADD INDEX fileAuditLogVersion (`version`)");
        $this->addSql("ALTER TABLE `ox_file_audit_log` ADD INDEX fileAuditLogId (`id`)");
        $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`,`last_workflow_instance_id`,`start_date`,`end_date`,`status`,`version`) VALUES (new.`id`, 'create', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`,new.`assoc_id`,new.`is_active`,new.`entity_id`,new.`last_workflow_instance_id`,new.`start_date`,new.`end_date`,new.`status`,new.`version`);");
        $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`,`last_workflow_instance_id`,`start_date`,`end_date`,`status`,`version`) VALUES (new.`id`, 'update', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`,new.`assoc_id`,new.`is_active`,new.`entity_id`,new.`last_workflow_instance_id`,new.`start_date`,new.`end_date`,new.`status`,new.`version`);");
        $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`,`last_workflow_instance_id`,`start_date`,`end_date`,`status`,`version`) VALUES (old.`id`, 'delete', old.`uuid`, old.`org_id`, old.`form_id`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`,old.`assoc_id`,old.`is_active`,old.`entity_id`,old.`last_workflow_instance_id`,old.`start_date`,old.`end_date`,old.`status`,old.`version`);");

        // Changes to File Attribute Audit Log Table and Creation of triggers
        // $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_insert`");
        // $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_update`");
        // $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_delete`");
        // $this->addSql("ALTER TABLE ox_file_attributes_audit_log ADD version INTEGER NOT NULL DEFAULT 0");
        // $this->addSql("CREATE TRIGGER `ox_file_attribute_insert` AFTER INSERT ON `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`, `field_value_type`, `field_value_text`, `field_value_numeric`, `field_value_boolean`, `field_value_date`,`version`) VALUES (new.`id`, 'create', new.`file_id`, new.`org_id`, new.`field_id`, new.`field_value`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`, new.`field_value_type`, new.`field_value_text`, new.`field_value_numeric`, new.`field_value_boolean`, new.`field_value_date`,new.`version`);");
        // $this->addSql("CREATE TRIGGER `ox_file_attribute_update` AFTER UPDATE on `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`, `field_value_type`, `field_value_text`, `field_value_numeric`, `field_value_boolean`, `field_value_date`,`version`) VALUES (new.`id`, 'update', new.`file_id`, new.`org_id`, new.`field_id`, new.`field_value`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`, new.`field_value_type`, new.`field_value_text`, new.`field_value_numeric`, new.`field_value_boolean`, new.`field_value_date`,new.`version`);");
        // $this->addSql("CREATE TRIGGER `ox_file_attribute_delete` AFTER DELETE ON ox_file_attribute FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`, `field_value_type`, `field_value_text`, `field_value_numeric`, `field_value_boolean`, `field_value_date`,`version`) VALUES (old.`id`, 'delete', old.`file_id`, old.`org_id`, old.`field_id`, old.`field_value`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`, old.`field_value_type`, old.`field_value_text`, old.`field_value_numeric`, old.`field_value_boolean`, old.`field_value_date`,old.`version`);");

        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
