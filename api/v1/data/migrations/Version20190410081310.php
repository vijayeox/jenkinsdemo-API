<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190410081310 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER  IF EXISTS  `ox_app_insert`");
        $this->addSql("DROP TRIGGER  IF EXISTS  `ox_app_update`");
        $this->addSql("DROP TRIGGER  IF EXISTS  `ox_app_delete`");


        
        $this->addSql("ALTER TABLE `ox_group` DROP COLUMN `type`,DROP COLUMN `cover_photo`");
        $this->addSql("ALTER TABLE `ox_app` CHANGE `isdeleted` `status` TINYINT(1) COMMENT '1-DELETED 2-INDRAFT 3-PREVIEW 4-PUBLISHED'");
        $this->addSql("ALTER TABLE `ox_app_audit_log` CHANGE `isdeleted` `status` TINYINT(1) COMMENT '1-DELETED 2-INDRAFT 3-PREVIEW 4-PUBLISHED'");

        $this->addSql("ALTER TABLE `ox_app` ADD INDEX ind_status (`status`)");
        $this->addSql("ALTER TABLE `ox_app_audit_log` ADD INDEX ind_regstatus (`status`)");
 

        $this->addSql("CREATE TRIGGER `ox_app_insert` AFTER INSERT ON `ox_app` FOR EACH ROW INSERT INTO `ox_app_audit_log` (`id`, `action`, `name`, `uuid`, `description`, `type`, `logo`, `category`, `date_created`, `date_modified`,`created_by`, `modified_by`, `status`) VALUES (new.`id`, 'create', new.`name`, new.`uuid`, new.`description`, new.`type`, new.`logo`, new.`category`, new.`date_created`, new.`date_modified`, new.`created_by`, new.`modified_by`, new.`status`);");

        $this->addSql("CREATE TRIGGER `ox_app_update` AFTER UPDATE ON `ox_app` FOR EACH ROW INSERT INTO `ox_app_audit_log` (`id`, `action`, `name`, `uuid`, `description`, `type`, `logo`, `category`, `date_created`, `date_modified`,`created_by`, `modified_by`, `status`) VALUES (new.`id`, 'update', new.`name`, new.`uuid`, new.`description`, new.`type`, new.`logo`, new.`category`, new.`date_created`, new.`date_modified`, new.`created_by`, new.`modified_by`, new.`status`);");

        $this->addSql("CREATE TRIGGER `ox_app_delete` AFTER DELETE ON `ox_app` FOR EACH ROW INSERT INTO `ox_app_audit_log` (`id`, `action`, `name`, `uuid`, `description`, `type`, `logo`, `category`, `date_created`, `date_modified`,`created_by`, `modified_by`, `status`) VALUES (old.`id`, 'update', old.`name`, old.`uuid`, old.`description`, old.`type`, old.`logo`, old.`category`, old.`date_created`, old.`date_modified`, old.`created_by`, old.`modified_by`, old.`status`)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs       
    	$this->addSql("ALTER TABLE `ox_group` ADD COLUMN  `cover_photo` VARCHAR(100) AFTER `logo`");
        $this->addSql("ALTER TABLE `ox_group` ADD COLUMN  `type` VARCHAR(100) AFTER `cover_photo`");
        $this->addSql("ALTER TABLE `ox_app` CHANGE `status` `isdeleted` TINYINT(1)");
        $this->addSql("ALTER TABLE `ox_app_audit_log` CHANGE `status` `isdeleted` TINYINT(1)");

        $this->addSql("DROP TRIGGER `ox_app_insert`");
        $this->addSql("DROP TRIGGER `ox_app_update`");
        $this->addSql("DROP TRIGGER `ox_app_delete`");
    }
}
