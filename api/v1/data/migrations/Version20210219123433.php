<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219123433 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `ox_app_entity` ADD COLUMN `enable_view` BOOLEAN DEFAULT true');
        $this->addSql('ALTER TABLE `ox_app_entity` ADD COLUMN `enable_auditlog` BOOLEAN DEFAULT true');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `enable_view`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `enable_auditlog`");
    }
}
