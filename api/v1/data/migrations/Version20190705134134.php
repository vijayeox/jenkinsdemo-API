<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190705134134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_role` ADD COLUMN `uuid` VARCHAR(40) NULL;");
        $this->addSql("UPDATE `ox_role` SET `uuid` = uuid();");
        $this->addSql("ALTER TABLE ox_role MODIFY `uuid` VARCHAR(40) NOT NULL");
        $this->addSql("ALTER TABLE `ox_role` ADD CONSTRAINT `uniq_uuid` UNIQUE (`uuid`);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_role` DROP COLUMN `uuid`;");
    }
}
