<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190806062307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE `ox_page_content` ADD `created_by` int(32) NOT NULL DEFAULT '1';");
        $this->addSql("ALTER TABLE `ox_page_content` ADD `modified_by` int(32) DEFAULT NULL;");
        $this->addSql("ALTER TABLE `ox_page_content` ADD `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;");
        $this->addSql("ALTER TABLE `ox_page_content` ADD `date_modified` datetime DEFAULT NULL;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_page_content` DROP COLUMN `modified_by`;");
        $this->addSql("ALTER TABLE `ox_page_content` DROP COLUMN `date_created`;");
        $this->addSql("ALTER TABLE `ox_page_content` DROP COLUMN `date_modified`;");
        $this->addSql("ALTER TABLE `ox_page_content` DROP COLUMN `created_by`;");
    }
}
