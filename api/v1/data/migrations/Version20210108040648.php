<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210108040648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_role` ADD COLUMN `app_id` INT(11) NULL AFTER `account_id`,
                       ADD CONSTRAINT FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`)");
        $this->addSql("ALTER TABLE ox_role DROP INDEX uniq_role");
        $this->addSql("ALTER TABLE `ox_role` ADD CONSTRAINT uniq_role UNIQUE (`name`,`account_id`,`app_id`)");
            
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_role` DROP COLUMN `app_id`");

    }
}
