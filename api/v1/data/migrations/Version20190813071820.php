<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190813071820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create User Cache Table for storing user metadata';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_user_cache` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `content` MEDIUMTEXT NULL , `app_id` INT(32) NULL, `user_id` INT(32) NULL , PRIMARY KEY ( `id` ),FOREIGN KEY (`user_id`) REFERENCES ox_user(`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_user_cache");
    }
}
