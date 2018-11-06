<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181106182324 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("CREATE TABLE `ox_organization` (
    		`id` int(32) NOT NULL AUTO_INCREMENT,
    		`name` varchar(100) CHARACTER SET utf8 NOT NULL,
    		`address` varchar(250),
    		`city` varchar(250),
    		`state` varchar(250),
    		`zip` varchar(250),
    		`logo` varchar(250),
    		`labelfile` varchar(250),
    		`languagefile` varchar(250),
    		`theme` varchar(250) ,
    		`status` varchar(250) NOT NULL,
			 PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1835 DEFAULT CHARSET=latin1;");
        $this->addSql("ALTER TABLE `ox_organization` ADD UNIQUE `orgIdIndex` (`id`);");
        $this->addSql("INSERT INTO `ox_organization` (`id`,`name`,`address`,`city`,`state`,`zip`,`logo`,`labelfile`,`languagefile`,`theme`,`status`) SELECT `id`,`name`,`address`,`city`,`state`,`zip`,`logo`,`labelfile`,`languagefile`,`themes`,`status` from `organizations`");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_ORGANIZATION',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_ORGANIZATION',15);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_organization");
    }
}
