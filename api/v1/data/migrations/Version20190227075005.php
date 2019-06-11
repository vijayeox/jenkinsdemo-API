<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190227075005 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("CREATE TABLE IF NOT EXISTS `ox_user` (
  			`id` int(11) NOT NULL AUTO_INCREMENT,
  			`uuid` varchar(128) NOT NULL UNIQUE,
			`username` varchar(100) NOT NULL,
			`password` varchar(32) NOT NULL,
			`firstname` varchar(50) DEFAULT NULL,
			`lastname` varchar(50) DEFAULT NULL,
			`name` varchar(100) DEFAULT NULL,
			`email` varchar(200) NOT NULL,
	        `orgid` int(11) NOT NULL,
		    `icon` varchar(100) DEFAULT NULL,
		    `status` varchar(10) NOT NULL DEFAULT 'Active',
	        `country` varchar(45) DEFAULT NULL,
			`date_of_birth` date DEFAULT NULL,
			`designation` varchar(45) DEFAULT NULL,
 		    `phone` varchar(30) DEFAULT NULL,
            `address` varchar(300) DEFAULT NULL,
			`gender` varchar(20) DEFAULT NULL,
			`website` varchar(100) DEFAULT NULL,
			`about` varchar(2000) CHARACTER SET latin1 DEFAULT NULL,
		    `interest` varchar(100) DEFAULT NULL,
			`hobbies` varchar(100) DEFAULT NULL,
			`managerid` int(11) DEFAULT NULL,
			`selfcontribute` tinyint(4) DEFAULT NULL,
			`contribute_percent` int(11) DEFAULT NULL,
			`eid` varchar(20) DEFAULT NULL,
			`signature` varchar(5000) DEFAULT NULL,
			`in_game` int(11) NOT NULL DEFAULT '0',
			`timezone` varchar(100) DEFAULT 'Asia/Kolkata',
			`date_created` datetime DEFAULT NULL,
			`date_modified` datetime DEFAULT NULL,
			`created_by` int(11) NOT NULL,
			`modified_by` int(11) DEFAULT NULL,
			`date_of_join` date DEFAULT NULL,
			`preferences` text DEFAULT NULL,
			PRIMARY KEY(id));");
    	$this->addSql("CREATE TRIGGER before_insert_ox_user BEFORE INSERT ON ox_user FOR EACH ROW SET new.uuid = uuid(), NEW.name = CONCAT(NEW.firstname, ' ', NEW.lastname);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE `ox_user`");
    	$this->addSql("DROP TRIGGER IF EXISTS `before_insert_ox_user`");
    }
}
