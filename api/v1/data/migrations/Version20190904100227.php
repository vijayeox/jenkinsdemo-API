<?php

declare (strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190904100227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("CREATE TABLE `ox_payment` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `app_id` varchar(100) NOT NULL COMMENT 'This is the uuid of the app that is using this payment',
            `payment_client` varchar(100) NOT NULL COMMENT 'This is to determine the payment gateways used (Converge or Authorize or something else)',
            `api_url` varchar(100) NOT NULL,
            `server_instance_name` varchar(100) DEFAULT NULL COMMENT 'Reference for Demo or Production',
            `payment_config` longtext COMMENT 'Typically a set of JSON array with the configuration list',
            `created_date` datetime DEFAULT NULL,
            `modified_date` datetime DEFAULT NULL,
            `created_id` bigint(20) DEFAULT NULL,
            `modified_id` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
          
        ");

        $this->addSql("CREATE TABLE IF NOT EXISTS ox_payment_trasaction (
            id INT NOT NULL AUTO_INCREMENT,
            payment_id INT NOT NULL,
            transaction_id varchar(100) NULL,
            transaction_status varchar(100) NULL,
            file_id INT NULL,
            PRIMARY KEY (`id`)
        )
        ENGINE=InnoDB
        DEFAULT CHARSET=utf8
        COLLATE=utf8_general_ci;
        ");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_payment`");
        $this->addSql("DROP TABLE `ox_payment_trasaction`");

    }
}
