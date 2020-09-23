<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813140650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add version number column to ox_app table.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_app ADD COLUMN version INT NOT NULL DEFAULT 0;");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_app DROP COLUMN version");
    }
}
