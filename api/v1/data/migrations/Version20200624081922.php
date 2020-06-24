<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200624081922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_subscriber` ADD CONSTRAINT fk_subscriber_file_id FOREIGN KEY (file_id) REFERENCES ox_file(id)");
        $this->addSql("ALTER TABLE `ox_subscriber` ADD CONSTRAINT fk_subscriber_organization_id FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_subscriber` ADD CONSTRAINT fk_subscriber_created_by FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_subscriber` ADD CONSTRAINT fk_subscriber_modified_by FOREIGN KEY (modified_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_subscriber` ADD CONSTRAINT fk_subscriber_user FOREIGN KEY (user_id) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_subscriber` ADD COLUMN `uuid` VARCHAR(250)");
        $this->addSql("UPDATE `ox_subscriber` set uuid = UUID()");
        $this->addSql("ALTER TABLE `ox_subscriber` ADD UNIQUE INDEX uq_ix_subscriber_uuid (uuid)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
