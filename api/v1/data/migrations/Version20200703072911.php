<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200703072911 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_entity_identifier` (
                 `entity_id` INT(10) NOT NULL REFERENCES ox_entity(`id`),
                 `identifier` VARCHAR(200) NOT NULL)");
        $this->addSql("ALTER TABLE ox_entity_identifier ADD INDEX ix_identifier (identifier)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_entity_identifier`");
    }
}
