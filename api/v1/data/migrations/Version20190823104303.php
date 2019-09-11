<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190823104303 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Updating Boolean Columns';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_dashboard MODIFY COLUMN isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY COLUMN ispublic BOOLEAN");
        $this->addSql("ALTER TABLE ox_datasource MODIFY COLUMN isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE ox_query MODIFY COLUMN isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE ox_query MODIFY COLUMN ispublic BOOLEAN");
        $this->addSql("ALTER TABLE ox_visualization MODIFY COLUMN isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE ox_widget MODIFY COLUMN isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE ox_widget MODIFY COLUMN ispublic BOOLEAN");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_dashboard MODIFY COLUMN isdeleted TINYINT");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY COLUMN ispublic TINYINT");
        $this->addSql("ALTER TABLE ox_datasource MODIFY COLUMN isdeleted TINYINT");
        $this->addSql("ALTER TABLE ox_query MODIFY COLUMN isdeleted TINYINT");
        $this->addSql("ALTER TABLE ox_query MODIFY COLUMN ispublic TINYINT");
        $this->addSql("ALTER TABLE ox_visualization MODIFY COLUMN isdeleted TINYINT");
        $this->addSql("ALTER TABLE ox_widget MODIFY COLUMN isdeleted TINYINT");
        $this->addSql("ALTER TABLE ox_widget MODIFY COLUMN ispublic TINYINT");
    }
}
