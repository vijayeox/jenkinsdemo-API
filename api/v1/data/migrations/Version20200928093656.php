<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200928093656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_organization ADD COLUMN parent_id INT(32)");
        $this->addSql("ALTER TABLE ox_organization ADD CONSTRAINT FOREIGN KEY (`parent_id`) REFERENCES ox_organization(`id`)");
        $this->addSql("ALTER TABLE ox_organization ADD COLUMN main_organization_id INT(32)");
        $this->addSql("UPDATE ox_organization set main_organization_id = id");
        $this->addSql("ALTER TABLE ox_organization change main_organization_id main_organization_id INT(32) ");
        $this->addSql("ALTER TABLE ox_organization ADD CONSTRAINT FOREIGN KEY (`main_organization_id`) REFERENCES ox_organization(`id`)");
        $this->addSql("CREATE TRIGGER `ox_organization_insert` BEFORE INSERT ON `ox_organization`
            FOR EACH ROW
            BEGIN
              IF NEW.main_organization_id IS NULL THEN
                SET NEW.main_organization_id := ( SELECT AUTO_INCREMENT 
                       FROM INFORMATION_SCHEMA.TABLES
                      WHERE TABLE_NAME = 'ox_organization'
                        AND TABLE_SCHEMA = DATABASE() );
              END IF;
            END;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
