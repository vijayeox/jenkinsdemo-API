<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190305141604 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("CREATE TABLE IF NOT EXISTS `ox_api_key` (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
    		`api_key` varchar(100) NOT NULL,
    		`secret` varchar(200) NOT NULL,
			`status` BOOLEAN NOT NULL DEFAULT false,
			`access_context` TEXT DEFAULT NULL,
			PRIMARY KEY (`id`));");
    	$this->addSql("INSERT INTO `ox_api_key` (`api_key`,`secret`,`status`) VALUES ('0cb6fd4c-40a5-11e9-a30d-1c1b0d785c98','MGNiNmZkNzUtNDBhNS0xMWU5LWEzMGQtMWMxYjBkNzg1Yzk4',1)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE ox_api_key");
    	$this->addSql("DROP TRIGGER IF EXISTS before_insert_api_key");
    }
}
