<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190215070604 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_ROLE',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_ROLE',3);");
    	$this->addSql("CREATE TABLE IF NOT EXISTS `ox_role` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`name` varchar(100) NOT NULL,
			`org_id` int(11) NOT NULL,
			`description` TEXT DEFAULT NULL,
			PRIMARY KEY (`id`));");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_role");
    	$this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_ROLE' ");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_ROLE'");

    }
}
