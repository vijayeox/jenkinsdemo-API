<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190207094744 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_contact_audit_log` 
            CHANGE COLUMN `phone_1` `phone_1` VARCHAR(45) NULL COMMENT '' ,
            CHANGE COLUMN `email` `email` VARCHAR(100) NULL ,
            CHANGE COLUMN `email_list` `email_list` TEXT NULL ,
            CHANGE COLUMN `address_1` `address_1` VARCHAR(500) NULL ;");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
