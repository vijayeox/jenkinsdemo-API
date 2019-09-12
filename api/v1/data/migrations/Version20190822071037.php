<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190822071037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE IF EXISTS  ox_file_attributes_audit_log;");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_delete`");
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_file_attributes_audit_log` (
            `id` int(32) NOT NULL AUTO_INCREMENT,
            `action` varchar(128) NULL,
            `attributeid` int(32) NULL,
            `fileid` int(64) NULL,
            `org_id` int(64) NULL,
            `fieldid` varchar(250) NULL,
            `fieldvalue` TEXT NULL,
            `created_by` int(32) NULL,
            `modified_by` int(32) NULL,
            `date_created` datetime NULL ,
            `date_modified` datetime NULL DEFAULT NULL,
            `server_info` varchar(1000) NULL DEFAULT NULL,
        PRIMARY KEY (`id`),  UNIQUE KEY `fileIndex` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_insert` AFTER INSERT ON `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `fileid`, `org_id`, `fieldid`, `fieldvalue`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`fileid`, new.`org_id`, new.`fieldid`, new.`fieldvalue`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_update` AFTER UPDATE on `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `fileid`, `org_id`, `fieldid`, `fieldvalue`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`fileid`, new.`org_id`, new.`fieldid`, new.`fieldvalue`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_delete` AFTER DELETE ON ox_file_attribute FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `fileid`, `org_id`, `fieldid`, `fieldvalue`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`fileid`, old.`org_id`, old.`fieldid`, old.`fieldvalue`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");
        $this->addSql("DROP TABLE IF EXISTS  ox_file_audit_log;");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_file_audit_log` (
            `id` int(32) NULL DEFAULT NULL,
            `action` varchar(128) NULL DEFAULT NULL,
            `uuid` varchar(128) NULL DEFAULT NULL,
            `org_id` int(64) NULL DEFAULT NULL,
            `form_id` int(32) NULL DEFAULT NULL,
            `activity_id` int(32) NULL DEFAULT NULL,
            `workflow_instance_id` TEXT NULL DEFAULT NULL,
            `data` text NULL DEFAULT NULL,
            `created_by` int(32) NULL DEFAULT NULL,
            `modified_by` int(32) NULL DEFAULT NULL,
            `date_created` datetime NULL DEFAULT NULL,
            `date_modified` datetime NULL DEFAULT NULL,
            `server_info` varchar(1000) NULL DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `activity_id`, `workflow_instance_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`uuid`, new.`org_id`, new.`form_id`, `activity_id`, `workflow_instance_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `activity_id`, `workflow_instance_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`uuid`, new.`org_id`, new.`form_id`, `activity_id`, `workflow_instance_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `activity_id`, `workflow_instance_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`uuid`, old.`org_id`, old.`form_id`, `activity_id`, `workflow_instance_id`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_delete`");
        $this->addSql("DROP TABLE ox_file_attributes_audit_log;");
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_file_attributes_audit_log` (
            `id` int(32) NOT NULL AUTO_INCREMENT,
            `action` varchar(128) NOT NULL,
            `attributeid` int(32) NOT NULL,
            `fileid` int(64) NOT NULL,
            `org_id` int(64) NOT NULL,
            `fieldid` varchar(250) NOT NULL,
            `fieldvalue` TEXT NULL,
            `created_by` int(32) NOT NULL DEFAULT '1',
            `modified_by` int(32) DEFAULT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` datetime DEFAULT NULL,
            `server_info` varchar(1000) DEFAULT NULL,
        PRIMARY KEY (`id`),  UNIQUE KEY `fileIndex` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_insert` AFTER INSERT ON `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `fileid`, `org_id`, `fieldid`, `fieldvalue`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`fileid`, new.`org_id`, new.`fieldid`, new.`fieldvalue`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_update` AFTER UPDATE on `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `fileid`, `org_id`, `fieldid`, `fieldvalue`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`fileid`, new.`org_id`, new.`fieldid`, new.`fieldvalue`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_delete` AFTER DELETE ON ox_file_attribute FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `fileid`, `org_id`, `fieldid`, `fieldvalue`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`fileid`, old.`org_id`, old.`fieldid`, old.`fieldvalue`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");
        $this->addSql("DROP TABLE ox_file_audit_log;");
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_file_audit_log` (
            `id` int(32) NOT NULL AUTO_INCREMENT,
            `fileid` int(32) NOT NULL,
            `action` varchar(128) NOT NULL,
            `uuid` varchar(128) NOT NULL,
            `org_id` int(64) NOT NULL,
            `form_id` int(32) NOT NULL,
            `data` text NOT NULL,
            `created_by` int(32) NOT NULL DEFAULT '1',
            `modified_by` int(32) DEFAULT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` datetime DEFAULT NULL,
            `server_info` varchar(1000) DEFAULT NULL,
        PRIMARY KEY (`id`),  UNIQUE KEY `fileIndex` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`uuid`, old.`org_id`, old.`form_id`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");
    }
}
