<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118183150 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_delete`");
        $this->addSql("ALTER TABLE ox_contact CHANGE `address1` `address_1` VARCHAR(500)");
        $this->addSql("ALTER TABLE ox_contact CHANGE `address2` `address_2` VARCHAR(500)");
        $this->addSql("ALTER TABLE ox_contact_audit_log CHANGE `address1` `address_1` VARCHAR(500)");
        $this->addSql("ALTER TABLE ox_contact_audit_log CHANGE `address2` `address_2` VARCHAR(500)");
        $this->addSql("CREATE TRIGGER `ox_contact_insert` AFTER INSERT on `ox_contact`
            FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`, `uuid`,`user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`,`company_name`,`designation`,`address_1`,`address_2`,`city`,`state`,`country`,`zip`,`owner_id`, `date_created`, `date_modified`) VALUES (new.`id`, 'insert', new.`uuid`,new.`user_id`, new.`first_name`, new.`last_name`, new.`phone_1`, new.`phone_list`, new.`email`, new.`email_list`, new.`company_name`,new.`designation`,new.`address_1`,new.`address_2`,new.`city`,new.`state`,new.`country`,new.`zip`, new.`owner_id`, new.`date_created`, new.`date_modified`);
            ");
        $this->addSql("CREATE TRIGGER `ox_contact_update` AFTER UPDATE on `ox_contact`
            FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`,  `uuid`,`user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`,`company_name`,`designation`,`address_1`,`address_2`,`city`,`state`,`country`,`zip`,`owner_id`, `date_created`, `date_modified`) VALUES (new.`id`, 'update',new.`uuid`, new.`user_id`, new.`first_name`, new.`last_name`, new.`phone_1`, new.`phone_list`, new.`email`, new.`email_list`, new.`company_name`,new.`designation`,new.`address_1`,new.`address_2`,new.`city`,new.`state`,new.`country`,new.`zip`, new.`owner_id`, new.`date_created`, new.`date_modified`);
            ");
        $this->addSql("CREATE TRIGGER ox_contact_delete AFTER DELETE ON ox_contact 
            FOR EACH ROW INSERT INTO `ox_contact_audit_log` (`id`, `action`, `uuid`,`user_id`, `first_name`, `last_name`, `phone_1`, `phone_list`, `email`, `email_list`,`company_name`,`designation`,`address_1`,`address_2`,`city`,`state`,`country`,`zip`,`owner_id`, `date_created`, `date_modified`) VALUES (old.`id`, 'insert',old.`uuid`, old.`user_id`, old.`first_name`, old.`last_name`, old.`phone_1`, old.`phone_list`, old.`email`, old.`email_list`, old.`company_name`,old.`designation`,old.`address_1`,old.`address_2`,old.`city`,old.`state`,old.`country`,old.`zip`, old.`owner_id`, old.`date_created`, old.`date_modified`);
            ");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_contact_delete`");
        $this->addSql("ALTER TABLE ox_contact CHANGE `address_1` `address1` VARCHAR(500)");
        $this->addSql("ALTER TABLE ox_contact CHANGE `address_2` `address2` VARCHAR(500)");
        $this->addSql("ALTER TABLE ox_contact_audit_log CHANGE `address_1` `address1` VARCHAR(500)");
        $this->addSql("ALTER TABLE ox_contact_audit_log CHANGE `address_2` `address2` VARCHAR(500)");
    }
}
