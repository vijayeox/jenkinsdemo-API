<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116073334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_file ADD CONSTRAINT FK_orgid FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE ox_file MODIFY workflow_instance_id INTEGER(32)");
        $this->addSql("ALTER TABLE ox_file ADD UNIQUE (uuid)");
        $this->addSql("ALTER TABLE ox_file ADD CONSTRAINT FK_modifiedBy FOREIGN KEY (modified_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE ox_file ADD CONSTRAINT FK_createdBy FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE ox_activity_field ADD CONSTRAINT FK_fieldId FOREIGN KEY (field_id) REFERENCES ox_field(id)");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file` DROP FOREIGN KEY FK_orgid");
        $this->addSql("ALTER TABLE ox_file DROP FOREIGN KEY FK_fieldId");
        $this->addSql("ALTER TABLE ox_file DROP FOREIGN KEY FK_modifiedBy");
        $this->addSql("ALTER TABLE ox_file DROP FOREIGN KEY FK_createdBy");
    }
}
