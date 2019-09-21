<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190815120902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove unused column';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_workflow` DROP COLUMN `process_keys`;");
        $this->addSql("ALTER TABLE `ox_file` ADD `form_id` int(32) NULL");
        $this->addSql("ALTER TABLE `ox_file` CHANGE COLUMN `activity_id` `activity_id` int(64) null  DEFAULT NULL;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_workflow` ADD `process_keys` TEXT NOT NULL");
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `form_id`;");
        $this->addSql("ALTER TABLE `ox_file` CHANGE COLUMN `activity_id` `activity_id` int(64) null  DEFAULT NULL;");
    }
}
