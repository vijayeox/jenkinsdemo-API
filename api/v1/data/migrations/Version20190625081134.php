<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625081134 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_delete`");

    	$this->addsql("ALTER TABLE ox_contact_audit_log DROP COLUMN `other`,DROP COLUMN `org_id`,DROP COLUMN `created_id`,DROP COLUMN `modified_id`");
    	$this->addSql("ALTER TABLE ox_contact_audit_log ADD COLUMN `designation` VARCHAR(45) AFTER `company_name`,ADD COLUMN `uuid` VARCHAR(128) AFTER `id`,ADD COLUMN `icon_type` TINYINT(1) AFTER `email_list`");

        $this->addsql("ALTER TABLE ox_contact DROP COLUMN `other`,DROP COLUMN `org_id`,DROP COLUMN `created_id`,DROP COLUMN `modified_id`");
        $this->addSql("ALTER TABLE ox_contact ADD COLUMN `designation` VARCHAR(45) AFTER `company_name`,ADD COLUMN `uuid` VARCHAR(128) AFTER `id`,ADD COLUMN `icon_type` TINYINT(1) AFTER `email_list`");


        $this->addSql("CREATE TRIGGER `ox_contact_update` AFTER UPDATE on `ox_contact`
        FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`,`uuid`, `user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`, `icon_type`,`company_name`,`designation`, `address_1`, `address_2`, `country`, `owner_id`, `date_created`, `date_modified`) VALUES (old.`id`, 'update', old.`uuid`, old.`user_id`, old.`first_name`, old.`last_name`, old.`phone_1`, old.`phone_list`, old.`email`, old.`email_list`,old.`icon_type`, old.`company_name`, old.`designation`,old.`address_1`, old.`address_2`, old.`country`, old.`owner_id`, old.`date_created`, old.`date_modified`);
        ");

        $this->addSql("CREATE TRIGGER ox_contact_delete AFTER DELETE ON ox_contact 
        FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`, `uuid`,`user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`,`icon_type`,`company_name`,`designation`, `address_1`, `address_2`, `country`, `owner_id`, `date_created`, `date_modified`) VALUES (old.`id`, 'delete', old.`uuid`,old.`user_id`, old.`first_name`, old.`last_name`, old.`phone_1`, old.`phone_list`, old.`email`, old.`email_list`,old.`icon_type`, old.`company_name`, old.`designation`,old.`address_1`, old.`address_2`, old.`country`, old.`owner_id`, old.`date_created`, old.`date_modified`)
        ");

       
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_delete`");
        
    	$this->addSql("ALTER TABLE ox_contact_audit_log ADD COLUMN `other` TEXT after `modified_id`,ADD COLUMN `org_id` INT(11) AFTER `owner_id`,ADD COLUMN `created_id` AFTER `org_id`,ADD COLUMN `modified_id` AFTER `created_id`");
      	$this->addSql("ALTER TABLE ox_contact_audit_log DROP COLUMN `desgination`,DROP COLUMN `uuid`,DROP COLUMN `icon_type`");
        $this->addSql("ALTER TABLE ox_contact ADD COLUMN `other` TEXT after `modified_id`,ADD COLUMN `org_id` INT(11) AFTER `owner_id`,ADD COLUMN `created_id` INT(11) AFTER `org_id`,ADD COLUMN `modified_id` INT(11) AFTER `created_id`");
        $this->addSql("ALTER TABLE ox_contact DROP COLUMN `desgination`,DROP COLUMN `uuid`,DROP COLUMN `icon_type`");
        
    }
}
