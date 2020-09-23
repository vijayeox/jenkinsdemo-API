<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200820040505 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove version number column from ox_app table.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_app DROP COLUMN version");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_app ADD COLUMN version INT NOT NULL DEFAULT 0");
    }
}

