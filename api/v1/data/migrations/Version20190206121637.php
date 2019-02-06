<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190206121637 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `rakshithapi_test`.`ox_contact` 
            CHANGE COLUMN `phone_2` `phone_list` TEXT NULL DEFAULT NULL ,
            ADD COLUMN `email_list` TEXT NULL AFTER `email`,
            ADD COLUMN `other` TEXT NULL AFTER `modified_id`;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
