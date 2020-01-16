<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116095333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_form MODIFY COLUMN template LONGBLOB");
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema) : void
    {

        $this->addSql("ALTER TABLE ox_form MODIFY COLUMN template LONGTEXT");
        // this down() migration is auto-generated, please modify it to your needs

    }
}
