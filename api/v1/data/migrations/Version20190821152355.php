<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190821152355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'change TASK ID to TEXT Field';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_activity` DROP COLUMN `task_id`;");
        $this->addSql("ALTER TABLE `ox_activity` ADD `task_id` TEXT NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_activity` DROP COLUMN `task_id`;");
        $this->addSql("ALTER TABLE `ox_activity` ADD `task_id` int(32) NULL");
    }
}
