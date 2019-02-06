<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190206115556 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_contact` 
CHANGE COLUMN `phone_1` `phone_1` VARCHAR(45) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `email` `email` VARCHAR(100) NULL DEFAULT NULL ,
CHANGE COLUMN `address_1` `address_1` VARCHAR(500) NULL DEFAULT NULL;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
