<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201015151815 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Modified foreign key options';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `arrowhead`.`ox_app_entity` DROP FOREIGN KEY `ox_app_entity_assoc_id_1`;");
        
        $this->addSql("ALTER TABLE `arrowhead`.`ox_app_entity` 
        ADD CONSTRAINT `ox_app_entity_assoc_id_1`
        FOREIGN KEY (`assoc_id`)
        REFERENCES `arrowhead`.`ox_app_entity` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `arrowhead`.`ox_app_entity` DROP FOREIGN KEY `ox_app_entity_assoc_id_1`;");
        
        $this->addSql("ALTER TABLE `arrowhead`.`ox_app_entity` 
        ADD CONSTRAINT `ox_app_entity_assoc_id_1`
        FOREIGN KEY (`assoc_id`)
        REFERENCES `arrowhead`.`ox_app_entity` (`id`)
        ON DELETE SET NULL
        ON UPDATE SET NULL;");

    }
}
