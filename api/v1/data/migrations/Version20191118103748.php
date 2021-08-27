<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118103748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add isdeleted flag for workflow table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_workflow` ADD COLUMN `isdeleted` INT(1) NULL DEFAULT 1");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP FOREIGN KEY activity_instance_references_form");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP COLUMN `form_id`");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_workflow` DROP COLUMN `isdeleted`;");
        $this->addSql("ALTER TABLE `ox_activity_instance` ADD COLUMN `form_id` INT(32) NULL DEFAULT NULL;");
        $this->addSql("ALTER TABLE `ox_activity_instance` ADD CONSTRAINT activity_instance_references_form FOREIGN KEY (form_id) REFERENCES ox_form(id)");
    }
}
