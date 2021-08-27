<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925101834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'A Column to set the rule for color code execution';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_entity` ADD COLUMN `ryg_rule` text AFTER `override_data`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `ryg_rule`");

    }
}
