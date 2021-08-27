<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120074109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_job` (
            `id` INT(32) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(200) NOT NULL,
            `app_id` INT(32) NOT NULL,
            `org_id` INT(32) NOT NULL,
            `job_id` VARCHAR(200) NOT NULL unique,
            `group_name` VARCHAR(100) NOT NULL,
            `config` LONGTEXT NOT NULL)");
        $this->addSql("ALTER TABLE `ox_job` ADD UNIQUE `ox_job_unique_name_group`(`name`, `group_name`)");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TABLE ox_job");
    }
}
