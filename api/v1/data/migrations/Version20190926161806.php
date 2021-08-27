<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190926161806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `ox_app_entity` (
              `id` int(32) NOT NULL AUTO_INCREMENT,
              `name` varchar(250) NOT NULL,
              `description` text,
              `app_id` int(11) NOT NULL,
              `created_by` int(32) NOT NULL DEFAULT '1',
              `modified_by` int(32) DEFAULT NULL,
              `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `date_modified` datetime DEFAULT NULL,
              `uuid` varchar(40) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `app_id` (`app_id`),
              CONSTRAINT `ox_app_entity_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `ox_app` (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_entity_field` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `entity_id` int(64) NOT NULL , `field_id` INT(64) NOT NULL , PRIMARY KEY ( `id` ),KEY `entity_id` (`entity_id`),
              CONSTRAINT `ox_entity_field_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `ox_app_entity` (`id`),KEY `field_id` (`field_id`),
              CONSTRAINT `ox_entity_field_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `ox_field` (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
        $this->addSql("ALTER TABLE ox_workflow ADD COLUMN `entity_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE ox_workflow ADD CONSTRAINT FK_WorkflowEntityId FOREIGN KEY (entity_id) REFERENCES ox_app_entity(id);");
        $this->addSql("ALTER TABLE ox_form ADD COLUMN `entity_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE ox_form ADD CONSTRAINT FK_FormEntityId FOREIGN KEY (entity_id) REFERENCES ox_app_entity(id);");
        $this->addSql("ALTER TABLE ox_field ADD COLUMN `entity_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE ox_field ADD CONSTRAINT FK_FieldEntityId FOREIGN KEY (entity_id) REFERENCES ox_app_entity(id);");
        $this->addSql("ALTER TABLE ox_file ADD COLUMN `entity_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE ox_file ADD CONSTRAINT FK_FileEntityId FOREIGN KEY (entity_id) REFERENCES ox_app_entity(id);");
        $this->addSql("ALTER TABLE ox_file ADD COLUMN `parent_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE ox_file ADD CONSTRAINT FK_FileParentId FOREIGN KEY (parent_id) REFERENCES ox_file(id);");
        $this->addSql("ALTER TABLE ox_activity ADD COLUMN `entity_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE ox_activity ADD CONSTRAINT FK_ActivityEntityId FOREIGN KEY (entity_id) REFERENCES ox_app_entity(id);");
        $this->addSql("ALTER TABLE ox_workflow_instance ADD COLUMN `parent_workflow_instance_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE ox_workflow_instance ADD CONSTRAINT FK_WorkflowInstanceParentId FOREIGN KEY (parent_workflow_instance_id) REFERENCES ox_workflow_instance(id);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_workflow` DROP  FOREIGN KEY `FK_WorkflowEntityId`;");
        $this->addSql("ALTER TABLE `ox_form` DROP  FOREIGN KEY `FK_FormEntityId`;");
        $this->addSql("ALTER TABLE `ox_field` DROP  FOREIGN KEY `FK_FieldEntityId`;");
        $this->addSql("ALTER TABLE `ox_file` DROP  FOREIGN KEY `FK_FileEntityId`;");
        $this->addSql("ALTER TABLE `ox_activity` DROP  FOREIGN KEY `FK_ActivityEntityId`;");
        $this->addSql("ALTER TABLE `ox_file` DROP  FOREIGN KEY `FK_FileParentId`;");
        $this->addSql("ALTER TABLE `ox_workflow` DROP COLUMN entity_id");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN entity_id");
        $this->addSql("ALTER TABLE `ox_field` DROP COLUMN entity_id");
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN entity_id");
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN parent_id");
        $this->addSql("ALTER TABLE `ox_activity` DROP COLUMN entity_id");
        $this->addSql("ALTER TABLE `ox_workflow_instance` DROP COLUMN parent_workflow_instance_id");
        $this->addSql("DROP TABLE ox_entity_field");
        $this->addSql("DROP TABLE ox_app_entity");
    }
}
