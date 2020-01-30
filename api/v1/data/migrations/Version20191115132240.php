<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191115132240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_menu` DROP FOREIGN KEY privilegeid");
        $this->addSql("ALTER TABLE `ox_app_menu` DROP COLUMN `privilege_id`");
        $this->addSql("ALTER TABLE `ox_app_menu` ADD COLUMN `privilege_name` VARCHAR(250) NULL");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_menu` DROP COLUMN `privilege_name`");

    }
}
