<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190315142058 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_mlet` (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(45) DEFAULT NULL,
            `appuuid` varchar(45),
            `name` varchar(200) NOT NULL,
            `description` varchar(500),
            `questiontext` varchar(255),
            `parameters` varchar(500),
            `orgid` int(11),
            `mletlist` varchar(255),
            `templateid` int(11),
            `querytext` varchar(1000),
            `html` text,
            `doctype` varchar(50),
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` datetime DEFAULT NULL,
            `created_id` int(11),
            `modified_id` int(11),              
        PRIMARY KEY(id));");            
        $this->addSql("CREATE TRIGGER  mlet_uuid_before_insert BEFORE INSERT ON ox_mlet FOR EACH ROW SET new.uuid = uuid();");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TABLE `ox_mlet`");
    	$this->addSql("DROP TRIGGER IF EXISTS `mlet_uuid_before_insert`");
    }
}
