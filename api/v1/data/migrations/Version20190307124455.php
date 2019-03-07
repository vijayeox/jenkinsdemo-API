<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307124455 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `ox_user`");
    	$this->addSql("INSERT INTO `ox_user` (`id`,`username`,`password`,`firstname`,`lastname`,`orgid`,`date_of_birth`,`date_of_join`,`gender`,`managerid`,`designation`,`country`,`phone`,`email`,`status`,`in_game`,`timezone`,`date_created`,`created_by`) VALUES (1,'bharatg','1619d7adc23f4f633f11014d2f22b7d8','Bharat','Gogineni',1,'1991-02-28 00:00:00','1991-02-28 00:00:00','Male',1,'IT ANALYST','Germany','+93-1234567891','bharatg@myvamla.com','Active',0,'United States/New York','2018-11-11 07:25:06',1)");
    	$this->addSql("INSERT INTO `ox_user` (`id`,`username`,`password`,`firstname`,`lastname`,`orgid`,`date_of_birth`,`date_of_join`,`gender`,`managerid`,`designation`,`country`,`phone`,`email`,`status`,`in_game`,`timezone`,`date_created`,`created_by`) VALUES (2,'karan','1619d7adc23f4f633f11014d2f22b7d8','Karan','Agarwal',1,'1991-02-28 00:00:00','1991-02-28 00:00:00','Male',1,'IT ANALYST','Ghana','+93-1234567891','test@va.com','Active',0,'United States/New York','2018-11-11 07:25:06',1)");
    	$this->addSql("INSERT INTO `ox_user` (`id`,`username`,`password`,`firstname`,`lastname`,`orgid`,`date_of_birth`,`date_of_join`,`gender`,`managerid`,`designation`,`country`,`phone`,`email`,`status`,`in_game`,`timezone`,`date_created`,`created_by`) VALUES (3,'rakshith','1619d7adc23f4f633f11014d2f22b7d8','rakshith','amin',1,'1991-02-28 00:00:00','1991-02-28 00:00:00','Male',1,'IT ANALYST','Ghana','+93-1234567891','test@va.com','Active',0,'United States/New York','2018-11-11 07:25:06',1)");
    	/*$this->addSql();
    	$this->addSql();*/
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM `ox_user`");
    }
}
