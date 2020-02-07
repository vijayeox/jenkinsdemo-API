<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191018085845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_payment ADD COLUMN `js_url` TEXT NULL DEFAULT NULL;");
        $this->addSql("DROP TABLE `ox_payment_trasaction`");
        $this->addSql("ALTER TABLE ox_payment ADD COLUMN `org_id` int(32) NOT NULL;");
        $this->addSql("CREATE TABLE IF NOT EXISTS ox_payment_transaction (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `payment_id` int(11) NOT NULL,
            `transaction_id` varchar(100) NULL,
            `transaction_status` varchar(100) NULL,
            `data` TEXT NULL DEFAULT NULL,
            `created_by` int(32) NOT NULL DEFAULT '1',
            `modified_by` int(32) DEFAULT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_modified` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
        $this->addSql("ALTER TABLE `ox_payment_transaction` ADD CONSTRAINT paymentId FOREIGN KEY (payment_id) REFERENCES ox_payment(id)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_payment DROP COLUMN `js_url`;");
        $this->addSql("ALTER TABLE ox_payment DROP COLUMN `org_id`;");
        $this->addSql("DROP TABLE `ox_payment_transaction`");
        $this->addSql("CREATE TABLE `ox_payment_trasaction` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `payment_id` int(11) NOT NULL,
          `transaction_id` varchar(100) DEFAULT NULL,
          `transaction_status` varchar(100) DEFAULT NULL,
          `data` longtext NOT NULL,
          PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->addSql("ALTER TABLE `ox_app_menu` DROP FOREIGN KEY paymentId");

    }
}
