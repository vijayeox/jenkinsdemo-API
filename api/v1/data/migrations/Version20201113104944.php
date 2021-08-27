<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113104944 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added a Column to Enable/Disable File Comments on Chat';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `ox_app` ADD COLUMN `chat_notification` BOOLEAN DEFAULT false AFTER `status`;');
        $this->addSql('ALTER TABLE `ox_app_entity` ADD COLUMN `subscriber_field` varchar(1024) NULL AFTER `status_field`;');
        $this->addSql("ALTER TABLE ox_subscriber DROP FOREIGN KEY fk_subscriber_modified_by");
        $this->addSql("ALTER TABLE `ox_subscriber` DROP COLUMN `modified_by`");
        $this->addSql("ALTER TABLE `ox_subscriber` DROP COLUMN `date_modified`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app` DROP COLUMN `chat_notification`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `subscriber_field`");
        $this->addSql('ALTER TABLE `ox_subscriber` ADD COLUMN `modified_by` INT(32) AFTER `created_by`;');
        $this->addSql('ALTER TABLE `ox_subscriber` ADD COLUMN `date_modified`  DATETIME AFTER `date_created`;');
        $this->addSql("ALTER TABLE `ox_subscriber` ADD CONSTRAINT fk_subscriber_modified_by FOREIGN KEY (modified_by) REFERENCES ox_user(id)");

    }
}
