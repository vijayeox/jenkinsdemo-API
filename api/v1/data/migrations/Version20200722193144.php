<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200722193144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'workflow to be associated with multiple entities';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_workflow_entity_mapper` (
           `workflow_id` INT(32) NOT NULL REFERENCES ox_workflow(`id`),
           `entity_id` INT(32) NOT NULL REFERENCES ox_app_entity(`id`),
           CONSTRAINT Unique_combination UNIQUE (workflow_id,entity_id))");
        $this->addSql("INSERT INTO ox_workflow_entity_mapper (workflow_id,entity_id)
            SELECT id,entity_id from ox_workflow");
        $this->addSql("ALTER TABLE ox_workflow_instance
            ADD column entity_id INT(32),
            ADD CONSTRAINT `entity_reference` FOREIGN KEY (`entity_id`) REFERENCES ox_app_entity(`id`)");
        $this->addSql("UPDATE ox_workflow_instance
            inner join ox_file on ox_workflow_instance.file_id = ox_file.id
            SET ox_workflow_instance.entity_id = ox_file.entity_id");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_workflow_entity_mapper");
        $this->addSql("ALTER TABLE ox_workflow_instance DROP FOREIGN KEY entity_reference");
        $this->addSql("ALTER TABLE ox_workflow_instance DROP column entity_id");
    }
}
