<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190829082107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql("ALTER TABLE ox_contact ADD COLUMN `address_id` INT NULL AFTER `designation`");
         $this->addSql("DROP PROCEDURE IF EXISTS OXCONTACT");
         $this->addSql("CREATE PROCEDURE OXCONTACT() 
                            BEGIN
                            DECLARE bDone INT;
                            DECLARE addressid INT;
                            DECLARE c_id INT;
                            DECLARE c_address1 VARCHAR(300);
                            DECLARE c_address2 VARCHAR(300);
                            DECLARE c_country VARCHAR(250);
                            DECLARE contact_cursor CURSOR FOR SELECT id,address_1,address_2,country FROM ox_contact;

                            DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
                            SET bDone = 0;
                            OPEN contact_cursor;
                            contactloop: loop
                               FETCH contact_cursor INTO c_id,c_address1,c_address2,c_country;
                               if bDone = 1 then leave contactloop; end if;
                                 if(c_address1 IS NULL) THEN 
                                    SET c_address1 = ' ';
                                 END if;
                                 INSERT INTO ox_address(`address1`,`address2`,`country`) VALUES (c_address1,c_address2,c_country);
                                 SET addressid = LAST_INSERT_ID();
                                 UPDATE ox_contact SET address_id = addressid WHERE id = c_id;
                            end loop contactloop;
                            CLOSE contact_cursor;
                            END;");
          $this->addSql("CALL OXCONTACT()");
          $this->addSql("DROP PROCEDURE IF EXISTS OXCONTACT");

          $this->addSql("ALTER TABLE ox_contact ADD CONSTRAINT fk_caddress_id FOREIGN KEY (`address_id`) REFERENCES ox_address(`id`)");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_contact ADD COLUMN `address_1` VARCHAR(300) NULL AFTER `designation`,ADD COLUMN `address_2` VARCHAR(300) NULL AFTER `address_1`,ADD COLUMN `country` VARCHAR(300) NULL AFTER `address_2`");
         $this->addSql("UPDATE ox_contact as ou join ox_address as oa on ou.address_id=oa.id SET ou.address_1 = oa.address1,ou.address_2 = oa.address2,ou.country=oa.country");
        $this->addSql("ALTER TABLE ox_conatct DROP COLUMN `address_id`");
    }
}
