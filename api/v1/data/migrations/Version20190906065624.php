<?php

declare (strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190906065624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_payment MODIFY COLUMN payment_client varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'This is to determine the payment gateways used (Converge or Authorize or something else)'");

        $this->addSql("ALTER TABLE ox_payment MODIFY COLUMN api_url varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
