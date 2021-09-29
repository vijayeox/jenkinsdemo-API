<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914060741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Invoice Table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_billing_customer` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(45) DEFAULT NULL,
            `account_id` int(11) DEFAULT NULL,
            `app_id` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`account_id`) REFERENCES ox_account(`id`),
            FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");

        $this->addSql("CREATE TABLE `ox_billing_invoice` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(45) DEFAULT NULL,
            `customer_id` int(11) DEFAULT NULL,
            `amount` double NOT NULL,
            `data` json DEFAULT NULL,
            `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
            `is_settled` INT(1) DEFAULT 0,
            `date_modified` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`customer_id`) REFERENCES ox_billing_customer(`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP table ox_billing_invoice");

    }
}
