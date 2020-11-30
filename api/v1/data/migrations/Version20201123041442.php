<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123041442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app` ADD COLUMN `app_properties` JSON AFTER `chat_notification`");
        $this->addSql("ALTER TABLE `ox_app` DROP COLUMN `chat_notification`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app` DROP COLUMN `app_properties`");
        $this->addSql('ALTER TABLE `ox_app` ADD COLUMN `chat_notification` BOOLEAN DEFAULT false AFTER `status`;');

    }
}
