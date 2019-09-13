<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190730110508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS ox_padi_verification_pl (
            id INT NOT NULL AUTO_INCREMENT,
            member_number varchar(6) NOT NULL,
            first_name varchar(16) NULL,
            MI varchar(1) NOT NULL,
            last_name varchar(21) NULL,
            address_1 varchar(100) NULL,
            address_2 varchar(100) NULL,
            address_international varchar(100) NULL,
            city varchar(50) NULL,
            state varchar(50) NULL,
            zip varchar(10) NULL,
            country_code varchar(4) NULL,
            home_phone varchar(16) NULL,
            work_phone varchar(16) NULL,
            insurance_type varchar(6) NULL,
            date_expire DATETIME NOT NULL,
            rating varchar(4) NULL,
            email varchar(100) NULL,
            PRIMARY KEY (`id`)
        )
        ENGINE=InnoDB
        DEFAULT CHARSET=utf8
        COLLATE=utf8_general_ci;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_padi_verification_pl;");

    }
}
