<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201027041029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added isdeleted column to mark the soft delete';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_activity` ADD COLUMN `isdeleted` BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD COLUMN `isdeleted` BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE `ox_field` ADD COLUMN `isdeleted` BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE `ox_form` ADD COLUMN `isdeleted` BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE `ox_workflow_instance` ADD COLUMN `isdeleted` BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE `ox_workflow_deployment` ADD COLUMN `isdeleted` BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE `ox_activity_instance` ADD COLUMN `isdeleted` BOOLEAN NOT NULL DEFAULT false");      

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_activity` DROP COLUMN `isdeleted`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `isdeleted`");
        $this->addSql("ALTER TABLE `ox_field` DROP COLUMN `isdeleted`");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN `isdeleted`");
        $this->addSql("ALTER TABLE `ox_workflow_instance` DROP COLUMN `isdeleted`");
        $this->addSql("ALTER TABLE `ox_workflow_deployment` DROP COLUMN `isdeleted`");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP COLUMN `isdeleted`");

    }
}
