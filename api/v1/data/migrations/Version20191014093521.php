<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014093521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_wf_user_identifier` DROP FOREIGN KEY `ox_wf_user_identifier_ibfk_2`");
        $this->addSql("ALTER TABLE `ox_wf_user_identifier` DROP COLUMN `workflow_id`");
        $this->addSql("ALTER TABLE `ox_wf_user_identifier` ADD COLUMN `workflow_instance_id` int(32) NOT NULL AFTER `id`,ADD CONSTRAINT FOREIGN KEY (`workflow_instance_id`) REFERENCES ox_workflow_instance(`id`);");
        $this->addSql("ALTER TABLE `ox_workflow_instance` DROP FOREIGN KEY workflow_instance_references_org");
        $this->addSql("ALTER TABLE `ox_workflow_instance` CHANGE COLUMN `org_id` `org_id` INT(64) NOT NULL , ADD CONSTRAINT FOREIGN KEY (`org_id`) REFERENCES ox_organization(`id`);");
        $this->addSql("ALTER TABLE `ox_workflow_instance` ADD CONSTRAINT workflow_instance_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` CHANGE COLUMN `fileid` `file_id` INT(64) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attribute` CHANGE COLUMN `fieldid` `field_id` INT(32) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attribute` CHANGE COLUMN `fieldvalue` `field_value` TEXT  NULL");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT FOREIGN KEY (`file_id`) REFERENCES ox_file(`id`);");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT FOREIGN KEY (`field_id`) REFERENCES ox_field(`id`);");
        $this->addSql("ALTER TABLE `ox_file_attributes_audit_log` CHANGE COLUMN `fileid` `file_id` INT(64) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attributes_audit_log` CHANGE COLUMN `fieldid` `field_id` INT(32) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attributes_audit_log` CHANGE COLUMN `fieldvalue` `field_value` TEXT  NULL");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_delete`");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_insert` AFTER INSERT ON `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`file_id`, new.`org_id`, new.`field_id`, new.`field_value`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_update` AFTER UPDATE on `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`file_id`, new.`org_id`, new.`field_id`, new.`field_value`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_delete` AFTER DELETE ON ox_file_attribute FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`file_id`, old.`org_id`, old.`field_id`, old.`field_value`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `is_active` int(2) NOT NULL DEFAULT '1' AFTER `parent_id`;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
