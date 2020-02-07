<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191003122950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_entity_field");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_entity_field` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `entity_id` int(64) NOT NULL , `field_id` INT(64) NOT NULL , PRIMARY KEY ( `id` ),KEY `entity_id` (`entity_id`),
              CONSTRAINT `ox_entity_field_ibfk_1` FOREIGN KEY (`entity_id`) REFERENCES `ox_app_entity` (`id`),KEY `field_id` (`field_id`),
              CONSTRAINT `ox_entity_field_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `ox_field` (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
    }
}
