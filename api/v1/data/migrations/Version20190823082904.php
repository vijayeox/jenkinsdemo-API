<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190823082904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Dropping the old widget as it is no longer required';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_widget");
        $this->addSql("DROP TABLE ox_screen");
        $this->addSql("DROP TABLE ox_screen_widget");
        $this->addSql("DROP TABLE ox_org_widget");
        $this->addSql("ALTER TABLE datasource RENAME TO ox_datasource");
        $this->addSql("ALTER TABLE query RENAME TO ox_query");
        $this->addSql("ALTER TABLE visualization RENAME TO ox_visualization");
        $this->addSql("ALTER TABLE widget RENAME TO ox_widget");
        $this->addSql("ALTER TABLE widget_dashboard_mapper RENAME TO ox_widget_dashboard_mapper");
        $this->addSql("ALTER TABLE dashboard RENAME TO ox_dashboard");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_datasource RENAME TO datasource");
        $this->addSql("ALTER TABLE ox_query RENAME TO query");
        $this->addSql("ALTER TABLE ox_visualization RENAME TO visualization");
        $this->addSql("ALTER TABLE ox_widget RENAME TO widget");
        $this->addSql("ALTER TABLE ox_widget_dashboard_mapper RENAME TO widget_dashboard_mapper");
        $this->addSql("ALTER TABLE ox_dashboard RENAME TO dashboard");
    }
}
