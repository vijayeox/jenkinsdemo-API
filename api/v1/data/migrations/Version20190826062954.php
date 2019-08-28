<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826062954 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Analytics database adjustments';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_widget` ADD `name` varchar(128) NOT NULL");
        $this->addSql("ALTER TABLE `ox_widget` ADD CONSTRAINT unique_name UNIQUE (name,org_id)");
        $this->addSql("ALTER TABLE `ox_datasource` ADD CONSTRAINT org_reference_datasource FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_query` ADD CONSTRAINT org_reference_query FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_visualization` ADD CONSTRAINT org_reference_visualization FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_widget` ADD CONSTRAINT org_reference_widget FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_dashboard` ADD CONSTRAINT org_reference_dashboard FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_datasource` CHANGE connection_string configuration TEXT NOT NULL");
        $this->addSql("ALTER TABLE `ox_query` CHANGE query_json configuration TEXT NOT NULL");
        $this->addSql("ALTER TABLE `ox_visualization` CHANGE type name varchar(128) NOT NULL");
        $this->addSql("ALTER TABLE `ox_widget` ADD configuration TEXT");
        $this->addSql("ALTER TABLE `ox_widget` ADD data TEXT");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_widget` DROP COLUMN name, DROP COLUMN configuration, DROP COLUMN data");
        $this->addSql("ALTER TABLE `ox_datasource` DROP FOREIGN KEY org_reference_datasource");
        $this->addSql("ALTER TABLE `ox_query` DROP FOREIGN KEY org_reference_query");
        $this->addSql("ALTER TABLE `ox_visualization` DROP FOREIGN KEY org_reference_visualization");
        $this->addSql("ALTER TABLE `ox_widget` DROP FOREIGN KEY org_reference_widget");
        $this->addSql("ALTER TABLE `ox_dashboard` DROP FOREIGN KEY org_reference_dashboard");
        $this->addSql("ALTER TABLE `ox_widget` DROP INDEX unique_name");
        $this->addSql("ALTER TABLE `ox_datasource` CHANGE configuration connection_string TEXT NOT NULL");
        $this->addSql("ALTER TABLE `ox_query` CHANGE configuration query_json TEXT NOT NULL");
        $this->addSql("ALTER TABLE `ox_visualization` CHANGE name type varchar(128) NOT NULL");
    }
}
