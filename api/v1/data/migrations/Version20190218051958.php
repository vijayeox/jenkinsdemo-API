<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190218051958 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_EMAIL',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_EMAIL',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (2, 'MANAGE_EMAIL',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (3, 'MANAGE_EMAIL',15);");
    	$this->addSql("CREATE TABLE IF NOT EXISTS `email_setting_user` (
    		`id` INT NOT NULL AUTO_INCREMENT,
			`userid` int(11) NOT NULL,
			`email` varchar(255) NOT NULL,
			`username` varchar(255) NOT NULL,
			`password` varchar(255) NOT NULL,
			`host` varchar(255) NOT NULL,
            `isdefault` BOOLEAN  DEFAULT false ,
			PRIMARY KEY (`id`));");
    	$this->addSql("CREATE TABLE IF NOT EXISTS `email_setting_server`(
    		`host` varchar(255) NOT NULL,
			`port` varchar(45) NOT NULL,
			`secure` varchar(45) NOT NULL,
			`smtp_host` varchar(255) NOT NULL,
			`smtp_port` varchar(45) NOT NULL,
			`smtp_username` varchar(100) NOT NULL,
			`smtp_password` varchar(255) NOT NULL,
			`smtp_secure` varchar(45) NOT NULL );");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE email_setting_user");
    	$this->addSql("DROP TABLE email_setting_server");
    	$this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_EMAIL' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_EMAIL'");
    }
}
