<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423091630 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app` MODIFY `uuid` varchar(40);");
        $this->addSql("ALTER TABLE `ox_app_registry` MODIFY `app_id` varchar(40);");
        $this->addSql("ALTER TABLE `ox_role_privilege` MODIFY `app_id` varchar(40);");
        $this->addSql("ALTER TABLE `ox_privilege` MODIFY `app_id` varchar(50);");


        $this->addSql("UPDATE `ox_app` SET `category` = 'utilities', `name` = 'Admin', `type` = 1,uuid = '946fd092-b4f7-4737-b3f5-14086541492e' WHERE `id` = 1");
        $this->addSql("UPDATE `ox_app` SET `category` = NULL, `name` = 'Announcements',`type` = 1,`uuid` = '66204208-d902-428b-95e4-0e190eba6dfc' WHERE `id` = 2");
        $this->addSql("UPDATE `ox_app` SET `name`= 'AppBuilder',`type` = 1,`uuid` = '0fc011f2-00ab-42cc-9de5-747ac6f47a2d' WHERE `id` = 3");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '4f9f3cd3-df8b-4986-9d7b-47dd8ef5124e' WHERE `id` = 4");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '9c9312ef-82bc-4179-b383-d9a7edeb725b' WHERE `id` = 5");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '4781052d-3080-4ec0-a1d6-a8697f6f0c21' WHERE `id` = 6");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '6a9eef08-0cbe-4534-9dda-4daacf9e9d31' WHERE `id` = 7");
        $this->addSql("UPDATE `ox_app` SET `uuid` = 'c41a840f-7714-428e-baa5-d720d5b989c7' WHERE `id` = 8");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`) VALUES ('CRM','25898992-85e8-492e-9705-5e39340c0cc9','organization','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}')");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`) VALUES ('MailAdmin','0b6f422a-64d9-45a5-8a22-992162845d86','utilities','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}')");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`) VALUES ('Settings','dcf4018e-b9ca-4df6-9617-0fe03fce06da','utilities','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}')");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`) VALUES ('ImageUploader','29919753-abae-4cb6-b5c2-948df7fb970f','collaboration','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"true\"}')");
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`category`,`date_created`,`created_by`,`status`,`start_options`) VALUES ('Preferences','f24cf4b0-e46a-4cde-83a2-1d276e55a783','office','2019-04-25 04:11:39',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}')");
    

        $this->addSql("UPDATE `ox_role_privilege` SET `permission` = 1 , `app_id` = '0b6f422a-64d9-45a5-8a22-992162845d86' WHERE `privilege_name` = 'MANAGE_EMAIL' ");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed` = 1 , `app_id` = '0b6f422a-64d9-45a5-8a22-992162845d86' WHERE `name` = 'MANAGE_EMAIL' ");
      
        $this->addSql("UPDATE `ox_app_registry` SET `app_id` = '946fd092-b4f7-4737-b3f5-14086541492e' WHERE `id` = 3");
       

        $this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) VALUES ('MANAGE_CRM',1,NULL,'25898992-85e8-492e-9705-5e39340c0cc9');");
        $this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) VALUES ('MANAGE_CRM',1,1,'25898992-85e8-492e-9705-5e39340c0cc9');");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (1,'MANAGE_CRM',1,1,'25898992-85e8-492e-9705-5e39340c0cc9');");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (2,'MANAGE_CRM',1,1,'25898992-85e8-492e-9705-5e39340c0cc9');");
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `ox_app` SET `category` = 'EXAMPLE_CATEGORY', `name` = 'Admin App', `type` = NULL,uuid = '5cb5ba10eb3ff' WHERE `id` = 1");
        $this->addSql("UPDATE `ox_app` SET `category` = 'EXAMPLE_CATEGORY', `name` = 'Announcement',`type` = NULL,`uuid` = '5cb5ba2e470cf' WHERE `id` = 2");
        $this->addSql("UPDATE `ox_app` SET `name`= 'App Builder',`type` = NULL,`uuid` = '5cb5ba2e470d6' WHERE `id` = 3");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '5cb5ba2e470d9' WHERE `id` = 4");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '5cb5ba2e470db' WHERE `id` = 5");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '5cb5ba2e470dc' WHERE `id` = 6");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '5cb5ba2e470dd' WHERE `id` = 7");
        $this->addSql("UPDATE `ox_app` SET `uuid` = '5cb5ba2e470df' WHERE `id` = 8");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'CRM'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'MailAdmin'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'Settings'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'ImageUploader'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'Preferences'");

        $this->addSql("UPDATE `ox_role_privilege` SET `permission` = 15 , `app_id` = NULL WHERE `privilege_name` = 'MANAGE_EMAIL'");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed` = 15 , `app_id` = NULL WHERE `name` = 'MANAGE_EMAIL'");
       
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_CRM'");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_CRM'");
        $this->addSql("UPDATE `ox_app_registry` SET `app_id` = '5cb5ba10eb3ff' WHERE `id` = 3");
        
    }
}
