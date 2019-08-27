<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827060934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
          $sql = "CREATE TABLE ox_address ( 
                    `id` INT( 11 ) AUTO_INCREMENT NOT NULL,
                    `address1` VarChar( 300 ) NOT NULL,
                    `address2` VarChar( 300 ) NULL,
                    `city` VarChar( 250 ) NULL,
                    `state` VarChar( 250 ) NULL,
                    `country` VarChar( 250 ) NULL,
                    `zip` VarChar( 250 ) NULL,
                    PRIMARY KEY ( `id` ) )
                ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
          $this->addSql($sql);
          $this->addSql("ALTER TABLE ox_user ADD COLUMN `address_id` INT NULL AFTER `phone`");
          $this->addSql("DROP PROCEDURE IF EXISTS OXADDRESS");
          $this->addSql("CREATE PROCEDURE OXADDRESS() 
                            BEGIN
                            DECLARE bDone INT;
                            DECLARE addressid INT;
                            DECLARE c_id INT;
                            DECLARE c_address VARCHAR(300);
                            DECLARE c_country VARCHAR(250);
                            DECLARE user_cursor CURSOR FOR SELECT id,address,country FROM ox_user;

                            DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
                            SET bDone = 0;
                            OPEN user_cursor;
                            adressloop: loop
                               FETCH user_cursor INTO c_id,c_address,c_country;
                               if bDone = 1 then leave adressloop; end if;
                                 if(c_address IS NULL) THEN 
                                    SET c_address = ' ';
                                 END if;
                                 INSERT INTO ox_address(`address1`,`country`) VALUES (c_address,c_country);
                                 SET addressid = LAST_INSERT_ID();
                                 UPDATE ox_user SET address_id = addressid WHERE id = c_id;
                            end loop adressloop;
                            CLOSE user_cursor;
                            END;");
          $this->addSql("CALL OXADDRESS()");
          $this->addSql("DROP PROCEDURE IF EXISTS OXADDRESS");
          $this->addSql("ALTER TABLE ox_user ADD CONSTRAINT fk_address_id FOREIGN KEY (`address_id`) REFERENCES ox_address(`id`)");

          $this->addSql("ALTER TABLE ox_organization ADD COLUMN `address_id` INT NULL AFTER `name`");
          $this->addSql("DROP PROCEDURE IF EXISTS OXORGADDRESS");
          $this->addSql("CREATE PROCEDURE OXORGADDRESS() 
                            BEGIN
                            DECLARE bDone INT;
                            DECLARE addressid INT;
                            DECLARE c_id INT;
                            DECLARE c_address VARCHAR(300);
                            DECLARE c_country VARCHAR(250);
                            DECLARE c_city VARCHAR(250);
                            DECLARE c_state VARCHAR(250);
                            DECLARE c_zip VARCHAR(250);
                            DECLARE org_cursor CURSOR FOR SELECT id,address,city,state,country,zip FROM ox_organization;

                            DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
                            SET bDone = 0;
                            OPEN org_cursor;
                            adressloop: loop
                               FETCH org_cursor INTO c_id,c_address,c_city,c_state,c_country,c_zip;
                               if bDone = 1 then leave adressloop; end if;
                                 if(c_address IS NULL) THEN 
                                    SET c_address = ' ';
                                 END if;
                                 INSERT INTO ox_address(`address1`,`city`,`state`,`country`,`zip`) VALUES (c_address,c_city,c_state,c_country,c_zip);
                                 SET addressid = LAST_INSERT_ID();
                                 UPDATE ox_organization SET address_id = addressid WHERE id = c_id;
                            end loop adressloop;
                            CLOSE org_cursor;
                            END;");
          $this->addSql("CALL OXORGADDRESS()");
          $this->addSql("DROP PROCEDURE IF EXISTS OXORGADDRESS");
          $this->addSql("ALTER TABLE ox_organization ADD CONSTRAINT fk_orgaddr_id FOREIGN KEY (`address_id`) REFERENCES ox_address(`id`)");
        
          $this->addSql("ALTER TABLE ox_user DROP COLUMN `address`,DROP COLUMN `country`");
          $this->addSql("ALTER TABLE ox_organization DROP COLUMN `address`,DROP COLUMN `city`,DROP COLUMN `state`,DROP COLUMN `country`,DROP COLUMN `zip`");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_address`");
        $this->addSql("ALTER TABLE ox_user ADD COLUMN `address` VARCHAR(300) NULL AFTER `status`,ADD COLUMN `country` VARCHAR(150) NULL AFTER `address`");
        $this->addSql("ALTER TABLE ox_organization ADD COLUMN `address` VARCHAR(250) NULL AFTER `name`,ADD COLUMN `city` VARCHAR(250) NULL AFTER `address`,ADD COLUMN `state` VARCHAR(250) NULL AFTER `city`,ADD COLUMN `country` VARCHAR(100) NULL AFTER `state`,ADD COLUMN `zip` VARCHAR(150) NULL AFTER `state`");

        $this->addSql("UPDATE ox_user as ou join ox_address as oa on ou.address_id=oa.id SET ou.address = oa.address1,ou.country=oa.country");
        $this->addSql("UPDATE ox_organization as og join ox_address as oa on og.address_id = oa.id SET og.address = oa.address1,og.city = oa.city,og.state = oa.state,og.country = oa.country,og.zip = oa.zip");
    }
}
