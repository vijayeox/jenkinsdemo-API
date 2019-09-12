<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423091630 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `ox_app` SET `category` = 'utilities', `name` = 'Admin', `type` = 1 WHERE `id` = 1");
        $this->addSql("UPDATE `ox_app` SET `category` = 'office', `name` = 'Announcements',`type` = 1 WHERE `id` = 2");
        $this->addSql("UPDATE `ox_app` SET `name`= 'AppBuilder',`type` = 1 WHERE `id` = 3");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`,`type`) VALUES ('CRM','".Uuid::uuid4()."','organization','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}', 1)");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`,`type`) VALUES ('MailAdmin','".Uuid::uuid4()."','utilities','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}', 1)");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`,`type`) VALUES ('Settings','".Uuid::uuid4()."','utilities','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}', 1)");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`,`type`) VALUES ('ImageUploader','".Uuid::uuid4()."','collaboration','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"true\"}', 1)");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`,`type`) VALUES ('Preferences','".Uuid::uuid4()."','office','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}', 1)");


        $this->addSql("UPDATE `ox_role_privilege` SET `permission` = 1 , `app_id` = (SELECT `id` from `ox_app` WHERE `name` LIKE 'MailAdmin') WHERE `privilege_name` = 'MANAGE_EMAIL' ");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed` = 1 , `app_id` = (SELECT `id` from `ox_app` WHERE `name` LIKE 'MailAdmin') WHERE `name` = 'MANAGE_EMAIL' ");

        $this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) SELECT 'MANAGE_CRM',1,NULL,id from ox_app where name LIKE 'CRM';");
        $this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) SELECT 'MANAGE_CRM',1,1,id from ox_app where name LIKE 'CRM';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) SELECT 1,'MANAGE_CRM',1,1,id from ox_app where name LIKE 'CRM';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) SELECT 2,'MANAGE_CRM',1,1,id from ox_app where name LIKE 'CRM';");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `ox_app` SET `category` = 'EXAMPLE_CATEGORY', `name` = 'Admin App', `type` = NULL WHERE `id` = 1");
        $this->addSql("UPDATE `ox_app` SET `category` = 'EXAMPLE_CATEGORY', `name` = 'Announcement',`type` = NULL WHERE `id` = 2");
        $this->addSql("UPDATE `ox_app` SET `name`= 'App Builder',`type` = NULL WHERE `id` = 3");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'CRM'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'MailAdmin'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'Settings'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'ImageUploader'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'Preferences'");

        $this->addSql("UPDATE `ox_role_privilege` SET `permission` = 15 , `app_id` = NULL WHERE `privilege_name` = 'MANAGE_EMAIL'");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed` = 15 , `app_id` = NULL WHERE `name` = 'MANAGE_EMAIL'");

        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_CRM'");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_CRM'");

    }
}
