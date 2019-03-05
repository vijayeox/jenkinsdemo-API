<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190227114943 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_app_audit_log` (
              `id` int(11) NOT NULL,
              `action` varchar(100) DEFAULT NULL,
              `name` varchar(200) NOT NULL,
              `uuid` varchar(20) NOT NULL,
              `description` text,
              `type` varchar(255) NOT NULL,
              `logo` varchar(255) NOT NULL,
              `category` varchar(255) NOT NULL,
              `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `date_modified` datetime DEFAULT NULL,
              `created_by` int(32) NOT NULL DEFAULT '1',
              `modified_by` int(32) DEFAULT NULL,
              `isdeleted` tinyint(1) DEFAULT '0',
              `server_info` varchar(1000) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

        $this->addSql("DROP TRIGGER  IF EXISTS  `ox_app_insert`");
        $this->addSql("DROP TRIGGER  IF EXISTS  `ox_app_update`");
        $this->addSql("DROP TRIGGER  IF EXISTS  `ox_app_delete`");

        $this->addSql("CREATE TRIGGER `ox_app_insert` AFTER INSERT ON `ox_app` FOR EACH ROW INSERT INTO `ox_app_audit_log` (`id`, `action`, `name`, `uuid`, `description`, `type`, `logo`, `category`, `date_created`, `date_modified`,`created_by`, `modified_by`, `isdeleted`) VALUES (new.`id`, 'create', new.`name`, new.`uuid`, new.`description`, new.`type`, new.`logo`, new.`category`, new.`date_created`, new.`date_modified`, new.`created_by`, new.`modified_by`, new.`isdeleted`);");

        $this->addSql("CREATE TRIGGER `ox_app_update` AFTER UPDATE ON `ox_app` FOR EACH ROW INSERT INTO `ox_app_audit_log` (`id`, `action`, `name`, `uuid`, `description`, `type`, `logo`, `category`, `date_created`, `date_modified`,`created_by`, `modified_by`, `isdeleted`) VALUES (new.`id`, 'update', new.`name`, new.`uuid`, new.`description`, new.`type`, new.`logo`, new.`category`, new.`date_created`, new.`date_modified`, new.`created_by`, new.`modified_by`, new.`isdeleted`);");

        $this->addSql("CREATE TRIGGER `ox_app_delete` AFTER DELETE ON `ox_app` FOR EACH ROW INSERT INTO `ox_app_audit_log` (`id`, `action`, `name`, `uuid`, `description`, `type`, `logo`, `category`, `date_created`, `date_modified`,`created_by`, `modified_by`, `isdeleted`) VALUES (old.`id`, 'update', old.`name`, old.`uuid`, old.`description`, old.`type`, old.`logo`, old.`category`, old.`date_created`, old.`date_modified`, old.`created_by`, old.`modified_by`, old.`isdeleted`)");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER `ox_app_insert`");
        $this->addSql("DROP TRIGGER `ox_app_update`");
        $this->addSql("DROP TRIGGER `ox_app_delete`");
    }
}
