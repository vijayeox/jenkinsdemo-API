<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215072658 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `rygStatus` VARCHAR(10) NOT NULL DEFAULT 'Active'");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD COLUMN `title` TEXT");
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `fileTitle` TEXT");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `rygStatus`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `title`");
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `fileTitle`");

    }
}
