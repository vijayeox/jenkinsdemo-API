<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190731130132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_workflow` CHANGE COLUMN `process_ids` `process_id` varchar(250) null;");
        $this->addSql("ALTER TABLE `ox_file` CHANGE COLUMN `workflow_instance_id` `workflow_instance_id` TEXT null;");
        $this->addSql("ALTER TABLE `ox_activity_instance` ADD `activity_id` int(32) NOT NULL;");
        $this->addSql("ALTER TABLE `ox_file` DROP `form_id`;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE `ox_workflow` CHANGE COLUMN `process_id` `process_ids` varchar(250) null;");
        $this->addSql("ALTER TABLE `ox_file` CHANGE COLUMN `workflow_instance_id` `workflow_instance_id` TEXT NOT null;");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP `activity_id`;");
        $this->addSql("ALTER TABLE `ox_file` DROP `activity_id`;");
        $this->addSql("ALTER TABLE `ox_file` ADD `form_id` int(32) NOT NULL;");
    }
}
