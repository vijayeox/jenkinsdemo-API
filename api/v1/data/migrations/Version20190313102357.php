<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190313102357 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `ox_user` 
ADD COLUMN `password_reset_code` VARCHAR(45) NULL DEFAULT NULL AFTER `preferences`,
ADD COLUMN `password_reset_expiry_date` DATETIME NULL DEFAULT NULL AFTER `password_reset_code`;
');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
