<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190617064303 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_activity_instance` ( 
            `id` INT(32) NOT NULL AUTO_INCREMENT , 
            `workflow_instance_id` INT(32) NOT NULL , 
            `activity_instance_id` VARCHAR(100) NOT NULL ,
            `assignee` VARCHAR(100) NOT NULL , 
            `group_id` INT(11) NOT NULL , 
            `form_id` INT(11) NOT NULL , 
            `status` VARCHAR(15) NOT NULL , 
            `start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `act_by_date`  DATETIME, 
            `org_id` INT(32) NOT NULL ,
             PRIMARY KEY (`id`), 
             FOREIGN KEY (`workflow_instance_id`) REFERENCES ox_workflow_instance(`id`)) ENGINE = InnoDB;");
        $this->addSql("ALTER TABLE `ox_group` MODIFY COLUMN `parent_id` INT NULL;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_activity_instance");
        $this->addSql("ALTER TABLE `ox_group` MODIFY COLUMN `parent_id` INT NOT NULL;");

    }
}
