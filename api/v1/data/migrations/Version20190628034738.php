<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628034738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Basic schema for Analytics';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `datasource` (
                `id` INT (11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(100) NOT NULL,
                `type` VARCHAR(100) NOT NULL,
                `connection_string` VARCHAR(255) NOT NULL,
                `created_by` INT (11) NOT NULL,
                `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`))");
        $this->addSql("CREATE TABLE `query` (
                `id` INT (11) NOT NULL AUTO_INCREMENT,
                `uuid` VARCHAR(36) NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `datasource_id` INT (11) NOT NULL,
                `query_json` TEXT NOT NULL,
                `ispublic` TINYINT NOT NULL DEFAULT 0,
                `created_by` INT (11) NOT NULL,
                `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`),
                FOREIGN KEY (`datasource_id`) REFERENCES datasource(`id`))");
        $this->addSql("CREATE TABLE `visualization` (
                `id` INT (11) NOT NULL AUTO_INCREMENT,
                `uuid` VARCHAR(36) NOT NULL,
                `type` VARCHAR(100) NOT NULL,
                `created_by` INT (11) NOT NULL,
                `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`))");
        $this->addSql("CREATE TABLE `widget` (
                `id` INT (11) NOT NULL AUTO_INCREMENT,
                `uuid` VARCHAR(36) NOT NULL,
                `query_id` INT (11) NOT NULL,
                `visualization_id` INT (11) NOT NULL,
                `ispublic` TINYINT NOT NULL DEFAULT 0,
                `created_by` INT (11) NOT NULL,
                `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`),
                FOREIGN KEY (`query_id`) REFERENCES query(`id`),
                FOREIGN KEY (`visualization_id`) REFERENCES visualization(`id`))");
        $this->addSql("CREATE TABLE `dashboard` (
                `id` INT (11) NOT NULL AUTO_INCREMENT,
                `uuid` VARCHAR(36) NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `ispublic` TINYINT NOT NULL DEFAULT 0,
                `layout_json` TEXT NOT NULL,
                `description` TEXT NULL,
                `dashboard_type` VARCHAR(100) NOT NULL,
                `created_by` INT (11) NOT NULL,
                `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`))");
        $this->addSql("CREATE TABLE `widget_dashboard_mapper` (
                `id` INT (11) NOT NULL AUTO_INCREMENT,
                `dashboard_id` INT (11) NOT NULL,
                `widget_id` INT (11) NOT NULL,
                `dimensions` VARCHAR(100) NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`dashboard_id`) REFERENCES dashboard(`id`),
                FOREIGN KEY (`widget_id`) REFERENCES widget(`id`))");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `widget_dashboard_mapper`");
        $this->addSql("DROP TABLE `dashboard`");
        $this->addSql("DROP TABLE `widget`");
        $this->addSql("DROP TABLE `visualization`");
        $this->addSql("DROP TABLE `query`");
        $this->addSql("DROP TABLE `datasource`");
    }
}
