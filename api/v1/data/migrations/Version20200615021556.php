<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615021556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Adding Index Column to Field Table
        $this->addSql("ALTER TABLE ox_field ADD COLUMN `index` tinyint(1) NOT NULL DEFAULT '0' AFTER `entity_id`");
        // Indexed File attribute table creation
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_indexed_file_attribute` ( 
            `id` INT(32) NOT NULL AUTO_INCREMENT  PRIMARY KEY, 
            `file_id` INT(64) NOT NULL , 
            `field_id` Int(32) NOT NULL ,  
            `org_id` INT(11) NOT NULL,
            `created_by` Int( 32 ) NOT NULL DEFAULT 1,
            `modified_by` Int( 32 ) NULL,
            `date_created` DateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` DateTime NULL,
            `field_value_text` VarChar( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `field_value_numeric` Float( 12, 0 ) NULL,
            `field_value_boolean` TinyInt( 1 ) NULL,
            `field_value_date` DateTime NULL,
            `field_value_type` Enum( 'OTHER', 'TEXT', 'DATE', 'NUMERIC', 'BOOLEAN' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'OTHER') ENGINE = InnoDB;");
        $this->addSql("ALTER TABLE ox_indexed_file_attribute ADD INDEX `numeric_value_indexed_field` (`field_value_numeric`)");
        $this->addSql("ALTER TABLE ox_indexed_file_attribute ADD INDEX `text_value_indexed_field` (`field_value_text`)");
        $this->addSql("ALTER TABLE ox_indexed_file_attribute ADD INDEX `boolean_value_indexed_field` (`field_value_boolean`)");
        $this->addSql("ALTER TABLE ox_indexed_file_attribute ADD INDEX `date_value_indexed_field` (`field_value_date`)");
        $this->addSql("ALTER TABLE `ox_indexed_file_attribute` ADD INDEX `fieldIdFileIdIndexed` (`id`, `file_id`);");
        $this->addSql("ALTER TABLE ox_indexed_file_attribute ADD INDEX `type_value_indexed` (`field_value_type`)");
        $this->addSql("ALTER TABLE `ox_indexed_file_attribute` ADD CONSTRAINT indexed_file_attribute_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_indexed_file_attribute` ADD CONSTRAINT indexed_file_attribute_references_field FOREIGN KEY (field_id) REFERENCES ox_field(id)");
        $this->addSql("ALTER TABLE `ox_indexed_file_attribute` ADD CONSTRAINT indexed_file_attribute_references_file FOREIGN KEY (file_id) REFERENCES ox_file(id)");
        $this->addSql("ALTER TABLE `ox_indexed_file_attribute` ADD CONSTRAINT indexed_file_attribute_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_indexed_file_attribute` ADD CONSTRAINT indexed_file_attribute_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_indexed_file_attribute");
        $this->addSql("ALTER TABLE ox_indexed_file_attribute DROP INDEX numeric_value_indexed_field,DROP INDEX text_value_indexed_field,DROP INDEX boolean_value_indexed_field,DROP INDEX date_value_indexed_field,DROP INDEX fieldIdFileIdIndexed,DROP INDEX type_value_indexed");


    }
}
