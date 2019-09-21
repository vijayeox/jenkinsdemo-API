<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903071544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_wf_user_identifier` ( `id` INT(32) NOT NULL AUTO_INCREMENT,`workflow_id` INT(32) NOT NULL , `user_id` INT(32) NOT NULL ,`identifier_name` VARCHAR(50) NOT NULL ,`identifier` VARCHAR(50) NOT NULL , PRIMARY KEY ( `id` ),FOREIGN KEY (`user_id`) REFERENCES ox_user(`id`),FOREIGN KEY (`workflow_id`) REFERENCES ox_workflow(`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_wf_user_identifier`");

    }
}
