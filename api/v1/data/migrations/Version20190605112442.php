<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605112442 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_file` DROP `name`;");
        $this->addSql("ALTER TABLE `ox_workflow_instance` MODIFY COLUMN `date_modified` DATETIME NULL;");
        $this->addSql("ALTER TABLE `ox_workflow_instance` MODIFY COLUMN `modified_by` INT(64) NULL;");
        $this->addSql("ALTER TABLE `ox_file` MODIFY COLUMN `workflow_instance_id` VARCHAR(128) NOT NULL;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
