<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200920172751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'add policy term field to ox_user table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_user ADD COLUMN policy_terms INT ");

    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_user DROP COLUMN policy_terms");

    }
}
