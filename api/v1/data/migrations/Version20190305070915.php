<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190305070915 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_file_audit_log` (
            `id` int(32) NOT NULL,
            `action` varchar(128) NOT NULL,
            `uuid` varchar(128) NOT NULL,
            `name` varchar(250) NOT NULL,
            `org_id` int(64) NOT NULL,
            `form_id` int(32) NOT NULL,
            `status` int(11) NOT NULL,
            `data` text NOT NULL,
            `created_by` int(32) NOT NULL DEFAULT '1',
            `modified_by` int(32) DEFAULT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` datetime DEFAULT NULL,
            `server_info` varchar(1000) DEFAULT NULL,
        PRIMARY KEY (`id`),  UNIQUE KEY `fileIndex` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `name`, `org_id`, `form_id`, `status`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`uuid`, new.`name`, new.`org_id`, new.`form_id`, new.`status`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `name`, `org_id`, `form_id`, `status`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`uuid`, new.`name`, new.`org_id`, new.`form_id`, new.`status`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `name`, `org_id`, `form_id`, `status`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`uuid`, old.`name`, old.`org_id`, old.`form_id`, old.`status`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");

    }
}
