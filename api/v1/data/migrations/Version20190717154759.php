<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190717154759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Modify Columns in App Page and Menu Tables';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_menu` ADD COLUMN `uuid` VARCHAR(40) NULL;");
        $this->addSql("ALTER TABLE `ox_app_menu` ADD COLUMN `permission` VARCHAR(100) NULL;");
        $this->addSql("ALTER TABLE `ox_app_page` ADD COLUMN `uuid` VARCHAR(40) NULL;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_menu` DROP COLUMN `uuid`;");
        $this->addSql("ALTER TABLE `ox_app_page` DROP COLUMN `uuid`;");
        $this->addSql("ALTER TABLE `ox_app_menu` DROP COLUMN `label`;");
        $this->addSql("ALTER TABLE `ox_app_menu` DROP COLUMN `permission`;");
    }
}
