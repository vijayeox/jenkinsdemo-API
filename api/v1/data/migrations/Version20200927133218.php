<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200927133218 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(" RENAME TABLE `ox_activity_instance_assignee` TO `ox_file_assignee`");
        $this->addSql("ALTER TABLE `ox_file_assignee` ADD COLUMN file_id INT NULL");
        $this->addSql("ALTER TABLE `ox_file_assignee` ADD KEY `file_id` (`file_id`);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(" RENAME TABLE `ox_file_assignee` TO `ox_activity_instance_assignee`");
        $this->addSql("ALTER TABLE `ox_file_assignee` DROP COLUMN file_id");
    }
}
