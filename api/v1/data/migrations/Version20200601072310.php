<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200601072310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Creation of User Profile Table
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_user_profile` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(128) NOT NULL UNIQUE,
            `org_id` int(11) NOT NULL,
            `firstname` varchar(50) DEFAULT NULL,
            `lastname` varchar(50) DEFAULT NULL,
            `email` varchar(200) NOT NULL,
            `date_of_birth` date DEFAULT NULL,
            `phone` varchar(30) DEFAULT NULL,
            `gender` varchar(20) DEFAULT NULL,
            `signature` varchar(5000) DEFAULT NULL,
            `address_id` INT(11),
            `date_created` datetime DEFAULT NULL,
            `date_modified` datetime DEFAULT NULL,
            `created_by` int(11) NOT NULL,
            `modified_by` int(11) DEFAULT NULL,
            `user_id` int(11)  NULL,
            PRIMARY KEY(id), FOREIGN KEY (`address_id`) REFERENCES ox_address(`id`),
            FOREIGN KEY (`org_id`) REFERENCES ox_organization(`id`));");
        $this->addSql("ALTER TABLE `ox_user_profile` ADD UNIQUE INDEX `userProfileUuidIndex` (`uuid`)");   
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_user_profile");
    }
}
