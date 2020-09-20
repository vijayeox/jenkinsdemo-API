<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611032505 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
            $this->addSql("CREATE TABLE IF NOT EXISTS `ox_organization_profile` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `uuid` varchar(128)NOT NULL,
             `name` varchar(100) CHARACTER SET utf8 NOT NULL,
             `address_id` INT(11) NOT NULL,
             `labelfile` varchar(250),
             `languagefile` varchar(250),
             `date_created` datetime DEFAULT NULL,
             `date_modified` datetime DEFAULT NULL,
             `created_by` int(11) NOT NULL,
             `modified_by` int(11) DEFAULT NULL,
             `org_id` int(11) NOT NULL,
              PRIMARY KEY (`id`),FOREIGN KEY (`address_id`) REFERENCES ox_address(`id`)
             ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
            $this->addSql("ALTER TABLE `ox_organization_profile` ADD INDEX `orgProfileIdIndex` (`id`);");
            $this->addSql("ALTER TABLE `ox_organization_profile` ADD UNIQUE INDEX `orgProfileUuidIndex` (`uuid`)");

            $this->addSql("INSERT INTO `ox_organization_profile` (`uuid`,`name`,`address_id`,`labelfile`,`languagefile`,`date_created`,`date_modified`,`created_by`,`modified_by`,`org_id`) SELECT UUID(),`ox_organization`.`name`,`ox_organization`.`address_id`,`ox_organization`.`labelfile`, `ox_organization`.`languagefile`,now(),now(),1,1,`ox_organization`.`id` from `ox_organization`;");

            $this->addSql("ALTER TABLE ox_organization ADD COLUMN `org_profile_id` int(11),ADD CONSTRAINT FOREIGN KEY (`org_profile_id`) REFERENCES ox_organization_profile(`id`)");
            $this->addSql("UPDATE ox_organization INNER JOIN ox_organization_profile on ox_organization_profile.org_id = ox_organization.id SET ox_organization.org_profile_id = ox_organization_profile.id");

            $this->addSql("ALTER TABLE `ox_organization_profile` DROP COLUMN `org_id`");

            $this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `name`");             
            $this->addSql("ALTER TABLE `ox_organization` DROP FOREIGN KEY `fk_orgaddr_id`");
            $this->addSql("ALTER TABLE `ox_organization` DROP INDEX `fk_orgaddr_id`");
            $this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `address_id`");
            $this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `labelfile`");
            $this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `languagefile`");    
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
         $this->addSql("DROP TABLE ox_organization_profile");

    }
}
