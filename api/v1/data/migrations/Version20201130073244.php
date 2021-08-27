<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130073244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE `ox_app_entity` ADD COLUMN `page_id` INT(32) DEFAULT false');
        $this->addSql('ALTER TABLE `ox_app_entity` ADD COLUMN `enable_comments` BOOLEAN DEFAULT false');
        $this->addSql('ALTER TABLE `ox_app_entity` ADD COLUMN `enable_documents` BOOLEAN DEFAULT false');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `page_id`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `enable_comments`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP COLUMN `enable_documents`");

    }
}
