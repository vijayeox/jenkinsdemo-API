<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190429172935 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_form_field` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `form_id` int(64) NOT NULL , `field_id` INT(64) NOT NULL , PRIMARY KEY ( `id` ) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN `org_id`");
    	$this->addSql("ALTER TABLE `ox_field` DROP COLUMN `form_id`");
    	$this->addSql("ALTER TABLE `ox_field` DROP COLUMN `org_id`");
        $this->addSql("ALTER TABLE `ox_field` MODIFY `required` int(2) NULL;");
        $this->addSql("ALTER TABLE `ox_field` MODIFY `sequence` int(2) NULL;");
        $this->addSql("ALTER TABLE `ox_workflow` MODIFY `process_ids` TEXT NULL;");
        $this->addSql("ALTER TABLE `ox_workflow` MODIFY `form_id` TEXT NULL;");
    	$this->addSql("ALTER TABLE `ox_field` ADD COLUMN `workflow_id` int(32) ");
        $this->addSql("ALTER TABLE `ox_form` ADD COLUMN `workflow_id` int(32)");
        $this->addSql("ALTER TABLE `ox_workflow` ADD COLUMN `file` TEXT NULL");
        $this->addSql("ALTER TABLE `ox_workflow` MODIFY COLUMN `name` TEXT NOT NULL;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_form_field");
        $this->addSql("ALTER TABLE `ox_form` ADD COLUMN `org_id` int(32)");
    	$this->addSql("ALTER TABLE `ox_field` ADD COLUMN `form_id` int(32) ");
    	$this->addSql("ALTER TABLE `ox_field` ADD COLUMN `org_id` int(32) ");
        $this->addSql("ALTER TABLE `ox_field` MODIFY `required` int(2) NOT NULL;");
        $this->addSql("ALTER TABLE `ox_field` MODIFY `sequence` int(2) NOT NULL;");
        $this->addSql("ALTER TABLE `ox_workflow` MODIFY `process_ids` TEXT NOT NULL;");
        $this->addSql("ALTER TABLE `ox_workflow` DROP COLUMN `file`");
    	$this->addSql("ALTER TABLE `ox_field` DROP COLUMN `workflow_id`");
        $this->addSql("ALTER TABLE `ox_form` DROP COLUMN `workflow_id`");
    }
}
