<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007041705 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add created by column to invoice table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_billing_invoice ADD `created_by` INT(11) DEFAULT NULL");
        $this->addSql("ALTER TABLE `ox_billing_invoice` ADD CONSTRAINT fk_invoice_created_by FOREIGN KEY (created_by) REFERENCES ox_user(id)");

        $this->addSql("ALTER TABLE ox_billing_invoice MODIFY `date_created` date DEFAULT NULL");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_billing_invoice DROP FOREIGN KEY fk_invoice_created_by");
        $this->addSql("ALTER TABLE ox_billing_invoice DROP COLUMN `created_by`");
        $this->addSql("ALTER TABLE ox_billing_invoice MODIFY `date_created` datetime DEFAULT CURRENT_TIMESTAMP");
    }
}
