<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180905054534 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_announcement` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(200) NOT NULL,
                `org_id` INT NOT NULL,
                `status` TINYINT NOT NULL DEFAULT 1,
                `description` TEXT NULL,
                `start_date` DATETIME NOT NULL,
                `end_date` DATETIME NOT NULL,
                `created_date` DATETIME NOT NULL,
                `created_id` INT NOT NULL,
                `media_type` VARCHAR(2000) NULL,
                `media_location` VARCHAR(2000) NULL,
                PRIMARY KEY (`id`))
              COMMENT = 'Table to store the list of all announcement for the organization';");

        $this->addSql("CREATE TABLE `ox_alert` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(200) NOT NULL,
                `org_id` INT NOT NULL,
                `status` TINYINT(4) NOT NULL DEFAULT 1,
                `description` TEXT NOT NULL,
                `created_date` DATETIME NOT NULL,
                `created_id` INT NOT NULL,
                PRIMARY KEY (`id`));");

        $this->addSql("CREATE TABLE `ox_announcement_group_mapper` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `announcement_id` INT NOT NULL,
                `group_id` INT NOT NULL,
                PRIMARY KEY (`id`));");
        $this->addSql("CREATE TABLE `user_alert_verfication` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `user_id` INT NOT NULL,
                `alert_id` INT NOT NULL,
                `status` TINYINT NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`));
              ");
        $this->addSql("ALTER TABLE `ox_announcement` ADD UNIQUE `orgIndex` (`id`, `org_id`);");
        $this->addSql("ALTER TABLE `ox_announcement_group_mapper` ADD UNIQUE `announcement_mapper` (`group_id`, `announcement_id`);");
        $this->addSql("ALTER TABLE `ox_announcement` ADD UNIQUE `created_index` (`id`, `created_id`);");
        $this->addSql("ALTER TABLE `user_alert_verfication` ADD UNIQUE `user_alert` (`user_id`, `alert_id`);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_announcement");
        $this->addSql("DROP TABLE ox_announcement_group_mapper");
        $this->addSql("DROP TABLE ox_alert");
        $this->addSql("DROP TABLE user_alert_verfication");
    }

}
