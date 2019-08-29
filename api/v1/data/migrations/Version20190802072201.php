<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190802072201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");
        $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`uuid`, new.`org_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`uuid`, new.`org_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`uuid`, old.`org_id`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");
        $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'create', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (new.`id`, 'update', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`);");
        $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`fileid`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`uuid`, old.`org_id`, old.`form_id`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`);");
    }
}
