<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200521100248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'File attribute trigger update';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_delete`");
        $this->addSql("ALTER TABLE ox_file_attribute MODIFY COLUMN field_value_type ENUM('OTHER','TEXT','DATE','NUMERIC','BOOLEAN') DEFAULT 'OTHER' NOT NULL");
        $this->addSql("ALTER TABLE ox_file_attributes_audit_log ADD COLUMN field_value_type ENUM('OTHER','TEXT','DATE','NUMERIC','BOOLEAN') NOT NULL");
        $this->addSql("ALTER TABLE ox_file_attributes_audit_log ADD COLUMN field_value_text VARCHAR(1024)");
        $this->addSql("ALTER TABLE ox_file_attributes_audit_log ADD COLUMN field_value_numeric FLOAT");
        $this->addSql("ALTER TABLE ox_file_attributes_audit_log ADD COLUMN field_value_boolean TINYINT(1)");
        $this->addSql("ALTER TABLE ox_file_attributes_audit_log ADD COLUMN field_value_date DATETIME");
        $this->addSql("UPDATE ox_file_attribute SET field_value_type = 'OTHER', field_value_text = NULL, field_value_numeric = NULL, field_value_boolean = NULL, field_value_date = NULL");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_insert` AFTER INSERT ON `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`, `field_value_type`, `field_value_text`, `field_value_numeric`, `field_value_boolean`, `field_value_date`) VALUES (new.`id`, 'create', new.`file_id`, new.`org_id`, new.`field_id`, new.`field_value`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`, new.`field_value_type`, new.`field_value_text`, new.`field_value_numeric`, new.`field_value_boolean`, new.`field_value_date`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_update` AFTER UPDATE on `ox_file_attribute` FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`, `field_value_type`, `field_value_text`, `field_value_numeric`, `field_value_boolean`, `field_value_date`) VALUES (new.`id`, 'update', new.`file_id`, new.`org_id`, new.`field_id`, new.`field_value`, new.`created_by`, new.`modified_by`, new.`date_created`, new.`date_modified`, new.`field_value_type`, new.`field_value_text`, new.`field_value_numeric`, new.`field_value_boolean`, new.`field_value_date`);");
        $this->addSql("CREATE TRIGGER `ox_file_attribute_delete` AFTER DELETE ON ox_file_attribute FOR EACH ROW INSERT INTO `ox_file_attributes_audit_log` (`attributeid`, `action`, `file_id`, `org_id`, `field_id`, `field_value`, `created_by`,`modified_by`, `date_created`, `date_modified`, `field_value_type`, `field_value_text`, `field_value_numeric`, `field_value_boolean`, `field_value_date`) VALUES (old.`id`, 'delete', old.`file_id`, old.`org_id`, old.`field_id`, old.`field_value`, old.`created_by`, old.`modified_by`, old.`date_created`, old.`date_modified`, old.`field_value_type`, old.`field_value_text`, old.`field_value_numeric`, old.`field_value_boolean`, old.`field_value_date`);");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa INNER JOIN ox_field of2 on of2.id = ofa.field_id Where of2.data_type = 'text') AS M on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_text = M.field_value, ox_file_attribute.field_value_type = 'TEXT'");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa INNER JOIN ox_field of2 on of2.id = ofa.field_id Where of2.data_type = 'numeric') AS M on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_numeric = M.field_value, ox_file_attribute.field_value_type = 'NUMERIC'");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa 
                        INNER JOIN ox_field of2 on of2.id = ofa.field_id Where of2.data_type = 'boolean') AS M 
                        on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_boolean = case when M.field_value='true' 
                        then 1 when M.field_value='false' then 0 else M.field_value End,
                        ox_file_attribute.field_value_type = 'BOOLEAN'");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa INNER JOIN ox_field of2 on of2.id = ofa.field_id Where of2.data_type = 'date' OR of2.data_type = 'datetime') AS M on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_date = M.field_value, ox_file_attribute.field_value_type = 'DATE'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
