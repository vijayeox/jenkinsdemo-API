<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200206073735 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_app_entity` ADD COLUMN `assoc_id` INT NULL AFTER `date_modified`");
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `assoc_id` INT NULL AFTER `parent_id`");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `assoc_id`");
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `assoc_id`");

    }
}
