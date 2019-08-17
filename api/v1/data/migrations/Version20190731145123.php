<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190731145123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_page_content` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `content` TEXT NULL , `form_id` INT(32) NULL, `page_id` INT(32) NOT NULL, `type` enum('Form','List','Document') NOT NULL,  `sequence` smallint NOT NULL DEFAULT '1' , PRIMARY KEY ( `id` ),FOREIGN KEY (`page_id`) REFERENCES ox_app_page(`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
        $this->addSql("ALTER TABLE `ox_app_page` DROP COLUMN `form_id`");
        $this->addSql("ALTER TABLE `ox_app_page` DROP COLUMN `text`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_page_content");
        $this->addSql("ALTER TABLE `ox_app_page` ADD `form_id` INT(32) NULL");
        $this->addSql("ALTER TABLE `ox_app_page` ADD `text` TEXT NULL");

    }
}
