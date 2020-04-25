<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413025041 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs 
            $this->addSql("ALTER TABLE ox_workflow_instance ADD COLUMN `file_id` INT(32) NULL AFTER `parent_workflow_instance_id`");
            $this->addSql("ALTER TABLE ox_workflow_instance ADD COLUMN `file_data` longtext NULL AFTER `file_id`");
            $this->addSql("ALTER TABLE `ox_activity_instance` MODIFY COLUMN `data` longtext NULL DEFAULT NULL;");
            $this->addSql("ALTER TABLE ox_workflow_instance DROP COLUMN `data`");
            $this->addSql("ALTER TABLE ox_file DROP COLUMN `activity_id`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_workflow_instance DROP COLUMN `file_id`");
        $this->addSql("ALTER TABLE ox_workflow_instance DROP COLUMN `file_data`");
        $this->addSql("ALTER TABLE ox_activity_instance DROP COLUMN `data`");
        $this->addSql("ALTER TABLE ox_workflow_instance ADD COLUMN `data` Text NULL AFTER `status`");
        $this->addSql("ALTER TABLE ox_file ADD COLUMN `activity_id` int(64) NULL AFTER `date_modified`");

    }
}
