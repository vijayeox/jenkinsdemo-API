<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511055552 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("CREATE TABLE  IF NOT EXISTS `ox_workflow_instance` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `workflow_id` int(64) NOT NULL , `app_id` INT(64) NOT NULL , `org_id` INT(64) NULL , `status` VARCHAR(500) NULL ,`data` TEXT NULL ,
		  `date_created` DATETIME NOT NULL,
		  `date_modified` DATETIME NOT NULL, `created_by` INT(64) NOT NULL , `modified_by` INT(64) NOT NULL , PRIMARY KEY ( `id` ) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
    	$this->addSql("ALTER TABLE `ox_file` ADD COLUMN `workflow_instance_id` int(32) ");
        $this->addSql("ALTER TABLE `ox_field` ADD COLUMN `template` TEXT NULL");
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `status`");
        $this->addSql("ALTER TABLE `ox_workflow` MODIFY `name` VARCHAR(500) NULL;");
        $this->addSql("TRUNCATE TABLE `ox_file`;");
        $this->addSql("TRUNCATE TABLE `ox_form`; ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE ox_workflow_instance");
        $this->addSql("ALTER TABLE `ox_file` DROP COLUMN `workflow_instance_id`");
    	$this->addSql("ALTER TABLE `ox_field` DROP COLUMN `template` ");
        $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `status` TEXT NULL");
    	$this->addSql("ALTER TABLE `ox_workflow` MODIFY `name` VARCHAR(500) ");
    }
}
