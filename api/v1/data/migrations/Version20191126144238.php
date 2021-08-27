<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191126144238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql ("ALTER TABLE ox_form DROP FOREIGN KEY FK_FormEntityId");
        $this->addSql ("ALTER TABLE ox_form DROP INDEX entityid_name_unique");
        $this->addSql ("ALTER TABLE ox_form ADD CONSTRAINT FK_FormEntityId FOREIGN KEY (`entity_id`) REFERENCES ox_app_entity(`id`)");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
