<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190227072337 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_contact_audit_log` (
              `id` int(11) NOT NULL,
              `action` varchar(100) DEFAULT NULL,
              `user_id` int(11) DEFAULT NULL,
              `first_name` varchar(100) NOT NULL,
              `last_name` varchar(100) DEFAULT NULL COMMENT '	',
              `phone_1` varchar(45) NOT NULL COMMENT '	',
              `phone_list` TEXT DEFAULT NULL,
              `email` varchar(100) NOT NULL,
              `email_list` TEXT NOT NULL,
              `company_name` varchar(100) DEFAULT NULL,
              `address_1` varchar(500) NOT NULL,
              `address_2` varchar(500) DEFAULT NULL,
              `country` varchar(45) DEFAULT NULL,
              `owner_id` int(11) NOT NULL,
              `org_id` int(11) NOT NULL,
              `created_id` int(11) NOT NULL,
              `date_created` datetime NOT NULL,
              `date_modified` datetime DEFAULT NULL,
              `modified_id` int(11) DEFAULT NULL,
              `server_info` varchar(1000) DEFAULT NULL,
              `other` TEXT DEFAULT NULL
            ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;");

        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_delete`");
        
        $this->addSql("CREATE TRIGGER `ox_contact_insert` AFTER INSERT on `ox_contact`
FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`, `user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`,`company_name`, `address_1`, `address_2`, `country`, `owner_id`, `org_id`, `created_id`, `date_created`, `date_modified`, `modified_id`, `other`) VALUES (new.`id`, 'update', new.`user_id`, new.`first_name`, new.`last_name`, new.`phone_1`, new.`phone_list`, new.`email`, new.`email_list`, new.`company_name`, new.`address_1`, new.`address_2`, new.`country`, new.`owner_id`, new.`org_id`, new.`created_id`, new.`date_created`, new.`date_modified`, new.`modified_id`, new.`other`);
");

        $this->addSql("CREATE TRIGGER `ox_contact_update` AFTER UPDATE on `ox_contact`
FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`, `user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`, `company_name`, `address_1`, `address_2`, `country`, `owner_id`, `org_id`, `created_id`, `date_created`, `date_modified`, `modified_id`, `other`) VALUES (new.`id`, 'update', new.`user_id`, new.`first_name`, new.`last_name`, new.`phone_1`, new.`phone_list`, new.`email`, new.`email_list`, new.`company_name`, new.`address_1`, new.`address_2`, new.`country`, new.`owner_id`, new.`org_id`, new.`created_id`, new.`date_created`, new.`date_modified`, new.`modified_id`, new.`other`);
");

        $this->addSql("CREATE TRIGGER ox_contact_delete AFTER DELETE ON ox_contact 
FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`, `user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`,`company_name`, `address_1`, `address_2`, `country`, `owner_id`, `org_id`, `created_id`, `date_created`, `date_modified`, `modified_id`, `other`) VALUES (old.`id`, 'delete', old.`user_id`, old.`first_name`, old.`last_name`, old.`phone_1`, old.`phone_list`, old.`email`, old.`email_list`, old.`company_name`, old.`address_1`, old.`address_2`, old.`country`, old.`owner_id`, old.`org_id`, old.`created_id`, old.`date_created`, old.`date_modified`, old.`modified_id`, old.`other`)
");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_delete`");

    }
}
