<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618044453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
         // Creation of Employee Table
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_employee` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(128) NOT NULL UNIQUE,
            `org_id` int(11) NOT NULL,
            `designation` varchar(45) DEFAULT NULL,
            `date_of_join` date DEFAULT NULL,
            `website` varchar(100) DEFAULT NULL,
            `about` varchar(2000) CHARACTER SET latin1 DEFAULT NULL,
            `interest` varchar(100) DEFAULT NULL,
            `hobbies` varchar(100) DEFAULT NULL,
            `managerid` int(11) DEFAULT NULL,
            `selfcontribute` tinyint(4) DEFAULT NULL,
            `contribute_percent` int(11) DEFAULT NULL,
            `eid` varchar(20) DEFAULT NULL,
            `date_created` datetime DEFAULT NULL,
            `date_modified` datetime DEFAULT NULL,
            `created_by` int(11) NOT NULL,
            `modified_by` int(11) DEFAULT NULL,
            `user_profile_id` int(11),
            `org_profile_id` int(11),
            PRIMARY KEY(id),FOREIGN KEY (`user_profile_id`) REFERENCES ox_user_profile(`id`),
            FOREIGN KEY (`org_id`) REFERENCES ox_organization(`id`),
            FOREIGN KEY (`org_profile_id`) REFERENCES ox_organization_profile(`id`));");
        $this->addSql("ALTER TABLE `ox_employee` ADD UNIQUE INDEX `employeeUuidIndex` (`uuid`)");

        //before_insert_ox_user - usr_profile new.userprofile id
        $this->addSql("ALTER TABLE `ox_user` ADD COLUMN `user_profile_id` INT(11) NULL AFTER `orgid`,ADD CONSTRAINT FOREIGN KEY (`user_profile_id`) REFERENCES ox_user_profile(`id`)");

        $this->addSql("INSERT INTO `ox_user_profile` (`uuid`,`firstname`,`lastname`,`email`,`date_of_birth`,`phone`,`gender`,`signature`,`address_id`,`user_id`,`date_created`,`date_modified`,`created_by`,`modified_by`,`org_id`) SELECT UUID(),`ox_user`.`firstname`,`ox_user`.`lastname`,`ox_user`.`email`, `ox_user`.`date_of_birth`, `ox_user`.`phone`, `ox_user`.`gender`, `ox_user`.`signature`,`ox_user`.`address_id`, `ox_user`.`id`,`ox_user`.`date_created`,`ox_user`.`date_modified`,`ox_user`.`created_by`,`ox_user`.`modified_by`,`ox_user`.`orgid` from `ox_user`;");

        $this->addSql("UPDATE ox_user INNER JOIN ox_user_profile on `ox_user_profile`.`user_id` = `ox_user`.`id` SET `ox_user`.`user_profile_id` = `ox_user_profile`.`id`");

        $this->addSql("INSERT INTO `ox_employee` (`uuid`,`designation`,`website`,`about`,`interest`,`hobbies`,`managerid`,`selfcontribute`,`contribute_percent`,`eid`,`date_created`,`date_modified`,`created_by`,`modified_by`,`org_profile_id`,`org_id`,`user_profile_id`,`date_of_join`) SELECT UUID(),`ox_user`.`designation`,`ox_user`.`website`,`ox_user`.`about`, `ox_user`.`interest`, `ox_user`.`hobbies`, `ox_user`.`managerid`, `ox_user`.`selfcontribute`,`ox_user`.`contribute_percent`, `ox_user`.`eid`,`ox_user`.`date_created`,`ox_user`.`date_modified`,`ox_user`.`created_by`,`ox_user`.`modified_by`,`ox_organization`.`org_profile_id`,`ox_organization`.`id`,`ox_user`.`user_profile_id`,`ox_user`.`date_of_join` from `ox_user` inner join ox_organization on `ox_user`.`orgid` = `ox_organization`.`id`;");

        // Creating Trigger
        $this->addSql("DROP TRIGGER IF EXISTS `before_insert_ox_user`");
        $this->addSql("CREATE TRIGGER before_insert_ox_user BEFORE INSERT ON ox_user FOR EACH ROW SET NEW.name = (SELECT CONCAT(firstname, ' ', lastname)from ox_user_profile where id=NEW.user_profile_id); IF(NEW.uuid IS NULL OR NEW.uuid = '') THEN SET NEW.uuid = uuid(); END IF;");

        $this->addSql("ALTER TABLE `ox_user_profile` DROP COLUMN `user_id`");
        // Dropping the un-related columns
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `firstname`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `lastname`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `email`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `date_of_birth`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `phone`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `gender`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `signature`");
        $this->addSql("ALTER TABLE `ox_user` DROP FOREIGN KEY `fk_address_id`");
        $this->addSql("ALTER TABLE `ox_user` DROP INDEX `fk_address_id`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `address_id`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `designation`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `website`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `about`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `interest`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `hobbies`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `managerid`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `selfcontribute`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `contribute_percent`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `eid`");
        $this->addSql("ALTER TABLE `ox_user` DROP COLUMN `date_of_join`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql("DROP TABLE ox_employee");


    }
}
