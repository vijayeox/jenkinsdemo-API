<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721093635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_business_role` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(128) NOT NULL UNIQUE,
            `name` varchar(255) NOT NULL UNIQUE,
            `date_created` datetime NOT NULL DEFAULT NOW(),
            `date_modified` datetime DEFAULT NULL,
            `created_by` int(11) NOT NULL,
            `modified_by` int(11) DEFAULT NULL,
            PRIMARY KEY(id));");
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_org_business_role` (
            `org_id` int(11) NOT NULL,
            `business_role_id` INT(11) NOT NULL,
            FOREIGN KEY (`business_role_id`) REFERENCES ox_business_role(`id`),
            FOREIGN KEY (`org_id`) REFERENCES ox_organization(`id`));");
        $this->addSql("ALTER TABLE `ox_role` ADD COLUMN `business_role_id` INT(11) NULL, ADD CONSTRAINT FOREIGN KEY (`business_role_id`) REFERENCES ox_business_role(`id`)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
