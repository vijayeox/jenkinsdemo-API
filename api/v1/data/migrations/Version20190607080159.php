<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607080159 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql("ALTER TABLE `ox_organization` ADD COLUMN `contactid` INT(40) AFTER `languagefile`");
		$this->addSql("ALTER TABLE `ox_organization` ADD COLUMN `country` VARCHAR(100) AFTER `state`");

		$this->addSql("INSERT INTO `ox_user` (`id`,`uuid`,`username`,`password`,`firstname`,`lastname`,`name`,`email`,`orgid`,`status`,`country`,`date_of_birth`,`designation`,`phone`,`gender`,`managerid`,`timezone`,`date_created`,`created_by`,`date_of_join`) VALUES (4,'768d1fb9-de9c-46c3-8d5c-23e0e484ce2e','clevadmin','1619d7adc23f4f633f11014d2f22b7d8','rohan','kumar','rohan kumar','rohan@gmail.com',1,'Active','US','1991-02-28','Admin','+91-1234567890',NULL,1,'United States/New York','2018-11-11 07:25:06',1,'2018-11-11 07:25:06');");
		$this->addSql("INSERT INTO `ox_user` (`id`,`uuid`,`username`,`password`,`firstname`,`lastname`,`name`,`email`,`orgid`,`status`,`country`,`date_of_birth`,`designation`,`phone`,`gender`,`managerid`,`timezone`,`date_created`,`created_by`,`date_of_join`) VALUES (5,'fbde2453-17eb-4d7f-909a-0fccc6d53e7a','goldadmin','1619d7adc23f4f633f11014d2f22b7d8','rakesh','kumar','rakesh kumar','rakesh@gmail.com',2,'Active','US','1991-02-28','Admin','+91-1234567890',NULL,1,'United States/New York','2018-11-11 07:25:06',1,'2018-11-11 07:25:06');");

		$this->addSql('UPDATE ox_user set preferences=\'{"soundnotification":"true","emailalerts":"false","timezone":"Asia/Calcutta","dateformat":"dd/mm/yyyy"}\' where orgid in (4,5);');
		
		$this->addSql("UPDATE `ox_organization` SET `contactid` = 4, `country` = 'US' where uuid = '53012471-2863-4949-afb1-e69b0891c98a'");
		$this->addSql("UPDATE `ox_organization` SET `contactid` = 5, `country` = 'US' where uuid = 'b0971de7-0387-48ea-8f29-5d3704d96a46'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
	    $this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `contactid`");
	    $this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `country`");
	    $this->addSql("DELETE FROM `ox_organization` WHERE `uuid` in('768d1fb9-de9c-46c3-8d5c-23e0e484ce2e','fbde2453-17eb-4d7f-909a-0fccc6d53e7a')");
    }
}
