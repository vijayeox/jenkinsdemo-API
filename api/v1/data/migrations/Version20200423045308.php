<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200423045308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_activity_instance ADD COLUMN `start_data` longtext NULL AFTER `activity_id`");
        $this->addSql("ALTER TABLE ox_activity_instance ADD COLUMN `completion_data` longtext NULL AFTER `start_data`");
        $this->addSql("UPDATE ox_activity_instance SET start_data = data");

        $this->addSql("ALTER TABLE ox_activity_instance DROP COLUMN `data`");
        $this->addSql("ALTER TABLE ox_activity_instance DROP COLUMN `act_by_date`");

        $this->addSql("ALTER TABLE `ox_activity_instance` ADD COLUMN `modified_by` int(32) DEFAULT NULL;");
        $this->addSql("ALTER TABLE `ox_activity_instance` ADD COLUMN `submitted_date` datetime DEFAULT NULL;");

        $this->addSql("ALTER TABLE ox_workflow_instance ADD COLUMN `start_data` longtext NULL AFTER `file_id`");
        $this->addSql("ALTER TABLE ox_workflow_instance ADD COLUMN `completion_data` longtext NULL AFTER `start_data`");
        $this->addSql("UPDATE ox_workflow_instance SET start_data = file_data");
        $this->addSql("ALTER TABLE ox_workflow_instance DROP COLUMN `file_data`");
        $this->addSql("UPDATE ox_activity_instance as oai join ox_workflow_instance as owi on owi.id = oai.workflow_instance_id join ox_file as `of` on of.id = owi.file_id SET oai.completion_data=of.data,oai.modified_by = of.modified_by,oai.submitted_date = of.date_modified");
        $this->addSql("UPDATE ox_workflow_instance as owi join ox_file as `of` on of.id = owi.file_id SET owi.completion_data=of.data,owi.date_modified = of.date_modified");
        $this->addSql("ALTER TABLE ox_field ADD COLUMN `type` VARCHAR(32) AFTER `data_type`");
        $this->addSql("ALTER TABLE ox_field ADD COLUMN `parent_id` INT(32) AFTER `type`");
        $this->addSql("UPDATE ox_field SET type = data_type");
        $this->addSql("UPDATE ox_field SET data_type = CASE type WHEN 'checkbox' THEN 'boolean'   WHEN 'currency' THEN 'numeric' WHEN 'phoneNumber' THEN 'numeric' WHEN 'number' THEN 'numeric'  WHEN 'datetime' THEN 'date' WHEN 'Date' THEN 'date' WHEN 'file' THEN 'file' WHEN 'document' THEN 'file' WHEN 'selectboxes' THEN 'selectboxes'  WHEN 'datagrid' THEN 'grid' WHEN 'editgrid' THEN 'grid' WHEN 'survey' THEN 'survey' ELSE 'text' END");
        $this->addSql("ALTER TABLE ox_field ADD CONSTRAINT FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`)");
        $this->addSql("ALTER TABLE ox_field ADD CONSTRAINT FOREIGN KEY (`entity_id`) REFERENCES ox_app_entity(`id`)");
        $this->addSql("ALTER TABLE ox_field ADD CONSTRAINT FOREIGN KEY (`parent_id`) REFERENCES ox_field(`id`)"); 

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
         $this->addSql("ALTER TABLE ox_activity_instance DROP COLUMN `start_data`");
        $this->addSql("ALTER TABLE ox_activity_instance DROP COLUMN `completion_data`");

        $this->addSql("ALTER TABLE `ox_activity_instance` DROP COLUMN `modified_by`");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP COLUMN `submitted_date`");

        $this->addSql("ALTER TABLE ox_workflow_instance DROP COLUMN `start_data`");
        $this->addSql("ALTER TABLE ox_workflow_instance DROP COLUMN `completion_data`");

    }
}
