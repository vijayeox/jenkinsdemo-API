<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180903181401 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `announcements` (
  		`id` int(11) AUTO_INCREMENT NOT NULL,
  		`avatarid` int(32) NOT NULL,
  		`date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  		`startdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  		`enddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  		`text` text NOT NULL,
  		`name` varchar(259) NOT NULL,
  		`groupid` int(32) DEFAULT NULL,
  		`orgid` int(32) NOT NULL,
  		`enabled` tinyint(1) NOT NULL DEFAULT '1',
  		`media_location` text NOT NULL,
  		`media_type` int(5) NOT NULL DEFAULT '1',
        PRIMARY KEY ( `id` ) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";
        $this->addSql($sql);

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
