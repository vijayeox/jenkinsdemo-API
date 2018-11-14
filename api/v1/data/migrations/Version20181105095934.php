<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181105095934 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_group` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(20000) CHARACTER SET utf8 NOT NULL,
			  `parent_id` int(11) NOT NULL,
			  `org_id` int(11) NOT NULL,
			  `manager_id` int(11) NOT NULL,
			  `description` mediumtext,
			  `logo` varchar(20) DEFAULT NULL,
			  `cover_photo` varchar(111) DEFAULT NULL,
			  `type` tinyint(4) NOT NULL DEFAULT '0',
			  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
			  `date_created` datetime NOT NULL,
			  `date_modified` datetime NOT NULL,
			  `created_id` int(11) NOT NULL,
			  `modified_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `orgid` (`orgid`),
			  KEY `status` (`status`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1");



        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_user_group` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `group_id` int(11) NOT NULL,
			  `avatar_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `avatarid` (`avatar_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
			SELECT * FROM rakshithapi.ox_user_group;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_group");
        $this->addSql("DROP TABLE ox_user_group");

    }
}
