<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004063702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_workflow` DROP FOREIGN KEY workflow_references_org");
        $this->addSql("ALTER TABLE `ox_workflow` DROP COLUMN `org_id`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_workflow ADD COLUMN `org_id` INT(32) NOT NULL");
        $this->addSql("ALTER TABLE `ox_workflow` ADD CONSTRAINT workflow_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
    }
}
