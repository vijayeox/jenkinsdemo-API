<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212113716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Foreign Keys for entity assocaition';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_file` ADD INDEX `FK_assoc_id` (`assoc_id` ASC)");
        $this->addSql("ALTER TABLE `ox_file` ADD CONSTRAINT `FK_FileAssocId` FOREIGN KEY (`assoc_id`) REFERENCES `ox_file` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD INDEX `assoc_id` (`assoc_id` ASC)");
        $this->addSql("ALTER TABLE `ox_app_entity` ADD CONSTRAINT `ox_app_entity_assoc_id_1` FOREIGN KEY (`assoc_id`) REFERENCES `ox_app_entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_file` DROP FOREIGN KEY `FK_FileAssocId`");
        $this->addSql("ALTER TABLE `ox_file` DROP INDEX `FK_assoc_id`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP FOREIGN KEY `ox_app_entity_assoc_id_1`");
        $this->addSql("ALTER TABLE `ox_app_entity` DROP INDEX `assoc_id`");
    }
}
