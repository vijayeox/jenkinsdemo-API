<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215093809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_job` DROP INDEX job_id ");
        $this->addSql("ALTER TABLE `ox_job` MODIFY COLUMN `job_id` VARCHAR(200) NULL;");
        $this->addSql("ALTER TABLE `ox_job` ADD UNIQUE INDEX unique_job (`app_id`,`account_id`,`job_id`)");
        $this->addSql("ALTER TABLE `ox_job` DROP INDEX ox_job_unique_name_group");
        $this->addSql("ALTER TABLE `ox_job` ADD UNIQUE INDEX ox_job_unique_name_group (`name`,`group_name`,`account_id`)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
