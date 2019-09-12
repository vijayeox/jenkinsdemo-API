<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827083055 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP COLUMN `assignee`;");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP COLUMN `group_id`;");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_activity_instance_assignee` ( `id` INT(32) NOT NULL AUTO_INCREMENT,`activity_instance_id` INT(32) NULL , `user_id` INT(32) NULL ,`group_id` INT(32) NULL ,`assignee` INT(1) NULL DEFAULT 0 , PRIMARY KEY ( `id` ),FOREIGN KEY (`user_id`) REFERENCES ox_user(`id`),FOREIGN KEY (`group_id`) REFERENCES ox_group(`id`),FOREIGN KEY (`activity_instance_id`) REFERENCES ox_activity_instance(`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE `ox_activity_instance` 
            ADD COLUMN `assignee` VARCHAR(250) NULL");
        $this->addSql("ALTER TABLE `ox_activity_instance` 
            ADD COLUMN `group_id` int(32) NULL");
        $this->addSql("DROP TABLE `ox_activity_instance_assignee`");
    }
}
