<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014093522 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file_attribute` MODIFY field_id INT(32) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_field FOREIGN KEY (field_id) REFERENCES ox_field(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_field` ADD CONSTRAINT field_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_field` ADD CONSTRAINT field_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file_attribute` MODIFY field_id VARCHAR(250) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_file");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_field");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_org");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_user");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_user_modified");
        $this->addSql("ALTER TABLE `ox_field` DROP FOREIGN KEY field_references_user");
        $this->addSql("ALTER TABLE `ox_field` DROP FOREIGN KEY field_references_user_modified");
    }
}
