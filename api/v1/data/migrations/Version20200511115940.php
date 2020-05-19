<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511115940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_project ADD COLUMN parent_id INT(11) NULL");
        $this->addSql("ALTER TABLE ox_project ADD CONSTRAINT FK_ParentId FOREIGN KEY (parent_id) REFERENCES ox_project(id)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_project DROP FOREIGN KEY FK_ParentId");
        $this->addSql("ALTER TABLE ox_project DROP COLUMN `parent_id`");
    }
}
