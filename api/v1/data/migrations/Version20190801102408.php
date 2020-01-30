<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190801102408 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding isdeleted flag to ensure soft delete';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE datasource ADD isdeleted TINYINT");
        $this->addSql("ALTER TABLE query ADD isdeleted TINYINT");
        $this->addSql("ALTER TABLE visualization ADD isdeleted TINYINT");
        $this->addSql("ALTER TABLE widget ADD isdeleted TINYINT");
        $this->addSql("ALTER TABLE dashboard ADD isdeleted TINYINT");
        $this->addSql("ALTER TABLE widget_dashboard_mapper DROP dimensions");
        $this->addSql("ALTER TABLE datasource ADD uuid VARCHAR(36) NOT NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE datasource DROP isdeleted");
        $this->addSql("ALTER TABLE query DROP isdeleted");
        $this->addSql("ALTER TABLE visualization DROP isdeleted");
        $this->addSql("ALTER TABLE widget DROP isdeleted");
        $this->addSql("ALTER TABLE dashboard DROP isdeleted");
        $this->addSql("ALTER TABLE widget_dashboard_mapper ADD dimensions VARCHAR(100) NOT NULL");
        $this->addSql("ALTER TABLE datasource DROP uuid");
    }
}
