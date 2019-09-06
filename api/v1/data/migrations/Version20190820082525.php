<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190820082525 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add FormID Default';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file` CHANGE COLUMN `form_id` `form_id` int(64) null  DEFAULT NULL;");
        $this->addSql("ALTER TABLE `ox_activity` ADD `task_id` int(32) NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file` CHANGE COLUMN `form_id` `form_id` int(64) null  DEFAULT NULL;");
        $this->addSql("ALTER TABLE `ox_activity` DROP COLUMN `task_id`;");

    }
}
