<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190429105845 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE from `ox_user_role`");
        $this->addSql("INSERT INTO ox_user_role (user_id, role_id) values (1, 1), (1, 2), (2, 2);");
        $this->addSql("ALTER TABLE `ox_user_role` ADD CONSTRAINT uniq_id UNIQUE (`user_id`,`role_id`)");
        $this->addSql("DELETE from `ox_app` where `name` in ('User','Project','Group','Role','Organization','Announcements','Settings','ImageUploader','Preferences')");

        
        $this->addSql("ALTER TABLE `ox_app` ADD COLUMN  `isdefault` TINYINT(1) AFTER `type`");
        $this->addSql("UPDATE `ox_app` SET `isdefault` = 1 WHERE `name` = 'Admin'");
        $this->addSql("UPDATE `ox_app` SET `type` = 1, `isdefault` = 0 WHERE `name` in ('CRM','MailAdmin','AppBuilder')");
        $this->addSql("UPDATE `ox_app_registry` SET `date_created` = now() WHERE app_id = (SELECT id from ox_app WHERE name LIKE 'Admin')");
    	$this->addSql("INSERT INTO `ox_app_registry` (`org_id`,`app_id`,`date_created`) SELECT 1,id,now() from ox_app WHERE name LIKE 'MailAdmin'");
    	$this->addSql("INSERT INTO `ox_app_registry` (`org_id`,`app_id`,`date_created`) SELECT 1,id,now() from ox_app WHERE name LIKE 'AppBuilder'");
    	$this->addSql("INSERT INTO `ox_app_registry` (`org_id`,`app_id`,`date_created`) SELECT 1,id,now() from ox_app WHERE name LIKE 'CRM'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`) VALUES ('User','4f9f3cd3-df8b-4986-9d7b-47dd8ef5124e','EXAMPLE_CATEGORY',now(),1)");
    	$this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`) VALUES ('Project','9c9312ef-82bc-4179-b383-d9a7edeb725b','EXAMPLE_CATEGORY',now(),1)");
    	$this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`) VALUES ('Group','4781052d-3080-4ec0-a1d6-a8697f6f0c21','EXAMPLE_CATEGORY',now(),1)");
    	$this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`) VALUES ('Role','6a9eef08-0cbe-4534-9dda-4daacf9e9d31','EXAMPLE_CATEGORY',now(),1)");
    	$this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`) VALUES ('Organization','c41a840f-7714-428e-baa5-d720d5b989c7','EXAMPLE_CATEGORY',now(),1)");

    	$this->addSql("UPDATE `ox_app` SET `type` = NULL WHERE `name` in ('CRM','MailAdmin')");
    	$this->addSql("ALTER TABLE `ox_app` DROP COLUMN  `isdefault`");

    	$this->addSql("UPDATE `ox_app_registry` SET `date_created` = NULL WHERE app_id = (SELECT id from ox_app WHERE name LIKE 'Admin')");
    	$this->addSql("DELETE from `ox_app_registry` where `app_id` in (SELECT id from ox_app)");
    }
}
