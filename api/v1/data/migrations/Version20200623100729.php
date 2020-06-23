<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623100729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_comment` ADD CONSTRAINT fk_comment_file_id FOREIGN KEY (file_id) REFERENCES ox_file(id)");
        $this->addSql("ALTER TABLE `ox_comment` ADD CONSTRAINT fk_comment_organization_id FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_comment` ADD CONSTRAINT fk_comment_parent_id FOREIGN KEY (parent) REFERENCES ox_comment(id)");
        $this->addSql("ALTER TABLE `ox_comment` ADD CONSTRAINT fk_comment_user_created FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_comment` ADD CONSTRAINT fk_comment_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_comment` ADD INDEX `idx_date_created` (`date_created`)");
        $this->addSql("ALTER TABLE `ox_comment` ADD INDEX `idx_is_deleted` (`isdeleted`)");
        $this->addSql("ALTER TABLE `ox_comment` ADD COLUMN `uuid` VARCHAR(250)");
        $this->addSql("UPDATE `ox_comment` set uuid = UUID()");
        $this->addSql("ALTER TABLE `ox_comment` ADD UNIQUE INDEX uq_ix_uuid (uuid)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
