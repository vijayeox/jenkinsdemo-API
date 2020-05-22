<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200520152552 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_file ADD COLUMN last_workflow_instance_id INT(32), ADD CONSTRAINT FK_workflow_instance_id FOREIGN KEY (`last_workflow_instance_id`) REFERENCES ox_workflow_instance(`id`)");
        $this->addSql("ALTER TABLE ox_file_audit_log ADD COLUMN last_workflow_instance_id INT(32)");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_delete`");
        $this->addSql("CREATE TRIGGER `ox_file_insert` AFTER INSERT ON `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`,`last_workflow_instance_id`) VALUES (new.`id`, 'create', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`,new.`assoc_id`,new.`is_active`,new.`entity_id`,new.`last_workflow_instance_id`);");
        $this->addSql("CREATE TRIGGER `ox_file_update` AFTER UPDATE on `ox_file` FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`,`last_workflow_instance_id`) VALUES (new.`id`, 'update', new.`uuid`, new.`org_id`, new.`form_id`, new.`data`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`,new.`assoc_id`,new.`is_active`,new.`entity_id`,new.`last_workflow_instance_id`);");
        $this->addSql("CREATE TRIGGER `ox_file_delete` AFTER DELETE ON ox_file FOR EACH ROW INSERT INTO `ox_file_audit_log` (`id`, `action`, `uuid`, `org_id`, `form_id`, `data`, `created_by`,`modified_by`, `date_created`, `date_modified`,`assoc_id`,`is_active`,`entity_id`,`last_workflow_instance_id`) VALUES (old.`id`, 'delete', old.`uuid`, old.`org_id`, old.`form_id`, old.`data`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`,old.`assoc_id`,old.`is_active`,old.`entity_id`,old.`last_workflow_instance_id`);");

        $this->addSql("UPDATE ox_file inner join (select wii.* from ox_workflow_instance as wii 
                                inner join (select file_id, max(date_created) as date_created from ox_workflow_instance group by file_id) as wid on wii.file_id = wid.file_id and wii.date_created = wid.date_created) as wi on wi.file_id = ox_file.id
                                SET ox_file.last_workflow_instance_id = wi.id");
        $this->addSql("ALTER TABLE ox_file_attribute ADD INDEX `type_value_index` (`field_value_type`)");
        $this->addSql("ALTER TABLE ox_field MODIFY COLUMN `name` VARCHAR(1024)");
        $this->addSql("ALTER TABLE ox_field ADD INDEX `name_index` (`name`)");
        $this->addSql("ALTER TABLE ox_workflow_instance ADD INDEX `status_index` (`status`)");


    }

    public function down(Schema $schema) : void
    {
        

    }
}
