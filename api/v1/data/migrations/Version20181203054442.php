<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203054442 extends AbstractMigration
{
	public function up(Schema $schema) : void
	{
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_PROJECT',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_PROJECT',15);");
		$this->addSql("CREATE TABLE `ox_project` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(200) NOT NULL,
			`org_id` int(11) NOT NULL,
			`description` TEXT NOT NULL,
			`created_by` INT(32) NOT NULL DEFAULT '1',
			`modified_by` INT(32) ,
			`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			`date_modified`  DATETIME,
			`isdeleted` BOOLEAN NOT NULL DEFAULT false ,
			PRIMARY KEY (`id`));");
		$this->addSql("CREATE TABLE `ox_user_project` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`user_id` INT(11) NOT NULL,
			`project_id` int(11) NOT NULL,
		PRIMARY KEY (`id`));");
	}
	public function down(Schema $schema) : void
	{
        // this down() migration is auto-generated, please modify it to your needs
		$this->addSql("DROP TABLE ox_project");
		$this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_PROJECT' ");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_PROJECT'");
		$this->addSql("DROP TABLE ox_user_project");
	}
}
