<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190107063348 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_COMMENT',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_COMMENT',15);");
    	$this->addSql("CREATE TABLE `ox_comment` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`org_id` int(11) NOT NULL,
			`parent` int(32),
            `file_id` int(32) NOT NULL,
			`text` TEXT NOT NULL,
			`created_by` INT(32) NOT NULL DEFAULT '1',
			`modified_by` INT(32) ,
			`date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			`date_modified`  DATETIME,
			`isdeleted` BOOLEAN NOT NULL DEFAULT false ,
			PRIMARY KEY (`id`));");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE ox_comment");
    	$this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_COMMENT' ");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_COMMENT'");
    }
}
