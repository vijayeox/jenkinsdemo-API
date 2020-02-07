<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190704125506 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add unique constraint for all UUID and required fields';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE query ADD CONSTRAINT unique_name UNIQUE (name,org_id)");
        $this->addSql("ALTER TABLE query ADD CONSTRAINT unique_uuid UNIQUE (uuid)");
        $this->addSql("ALTER TABLE visualization ADD CONSTRAINT unique_type UNIQUE (type,org_id)");
        $this->addSql("ALTER TABLE visualization ADD CONSTRAINT unique_uuid UNIQUE (uuid)");
        $this->addSql("ALTER TABLE widget ADD CONSTRAINT unique_uuid UNIQUE (uuid)");
        $this->addSql("ALTER TABLE dashboard ADD CONSTRAINT unique_uuid UNIQUE (uuid)");
        $this->addSql("ALTER TABLE dashboard ADD CONSTRAINT unique_name UNIQUE (name,org_id)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE query DROP INDEX unique_name");
        $this->addSql("ALTER TABLE query DROP INDEX unique_uuid");
        $this->addSql("ALTER TABLE visualization DROP INDEX unique_type");
        $this->addSql("ALTER TABLE visualization DROP INDEX unique_uuid");
        $this->addSql("ALTER TABLE dashboard DROP INDEX unique_name");
        $this->addSql("ALTER TABLE dashboard DROP INDEX unique_uuid");
        $this->addSql("ALTER TABLE widget DROP INDEX unique_uuid");
    }
}
