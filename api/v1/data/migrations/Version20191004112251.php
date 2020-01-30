<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004112251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Field increase Options Size';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE `ox_field` CHANGE COLUMN `options` `options` TEXT NULL DEFAULT NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE `ox_field` CHANGE COLUMN `options` `options` VARCHAR(1000) NULL DEFAULT NULL");
    }
}
