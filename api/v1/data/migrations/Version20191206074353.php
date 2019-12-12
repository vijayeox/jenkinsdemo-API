<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191206074353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Changing the default value for version';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_datasource ALTER version SET DEFAULT 1");
        $this->addSql("ALTER TABLE ox_query ALTER version SET DEFAULT 1");
        $this->addSql("ALTER TABLE ox_visualization ALTER version SET DEFAULT 1");
        $this->addSql("ALTER TABLE ox_dashboard ALTER version SET DEFAULT 1");
        $this->addSql("ALTER TABLE ox_widget ALTER version SET DEFAULT 1");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_datasource ALTER version SET DEFAULT 0");
        $this->addSql("ALTER TABLE ox_query ALTER version SET DEFAULT 0");
        $this->addSql("ALTER TABLE ox_visualization ALTER version SET DEFAULT 0");
        $this->addSql("ALTER TABLE ox_dashboard ALTER version SET DEFAULT 0");
        $this->addSql("ALTER TABLE ox_widget ALTER version SET DEFAULT 0");
    }
}
