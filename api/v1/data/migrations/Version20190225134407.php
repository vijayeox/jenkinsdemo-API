<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Oxzion\Utils\UuidUtil;

/**
 * Auto-generated Migration: Please modify to your needs!
 */

final class Version20190225134407 extends AbstractMigration
{
	
	public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$uniqueIdString = 'f297dd6a-3eb4-4e06-83ad-fb289e5c0535';
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Admin App','EXAMPLE_CATEGORY','$uniqueIdString',2);");
        $this->addSql("INSERT INTO `ox_app_registry` (org_id,app_id,start_options) SELECT 1,id ,'json_object' from ox_app where `name` LIKE 'Admin App';");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed`=3,`app_id`=( SELECT id from ox_app WHERE name LIKE 'Admin App') WHERE `name` LIKE 'MANAGE_USER';");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed`=3,`app_id`=( SELECT id from ox_app WHERE name LIKE 'Admin App') WHERE `name` LIKE 'MANAGE_PROJECT';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString') WHERE `name` LIKE 'MANAGE_ANNOUNCEMENT';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString') WHERE `name` LIKE 'MANAGE_ROLE';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString') WHERE `name` LIKE 'MANAGE_GROUP';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString') WHERE `name` LIKE 'MANAGE_ORGANIZATION';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString') WHERE `name` LIKE 'MANAGE_APP';");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString'),org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_ANNOUNCEMENT' AND `role_id` = 1");
//        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_APP' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString'),org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_ROLE' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString'),org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_GROUP' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString'),org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_ORGANIZATION' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString'),org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_USER' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`=( SELECT id from ox_app WHERE uuid = '$uniqueIdString'),org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_PROJECT' AND `role_id` = 1");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Announcement','EXAMPLE_CATEGORY','".UuidUtil::uuid()."',2);");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('App Builder','EXAMPLE_CATEGORY','".UuidUtil::uuid()."',2);");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('User','EXAMPLE_CATEGORY','".UuidUtil::uuid()."',2);");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Project','EXAMPLE_CATEGORY','".UuidUtil::uuid()."',2);");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Group','EXAMPLE_CATEGORY','".UuidUtil::uuid()."',2);");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Role','EXAMPLE_CATEGORY','".UuidUtil::uuid()."',2);");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Organization','EXAMPLE_CATEGORY','".UuidUtil::uuid()."',2);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$uniqueIdString = 'f297dd6a-3eb4-4e06-83ad-fb289e5c0535';
    	$this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'Admin App' AND `category` LIKE 'EXAMPLE_CATEGORY';");
    	$this->addSql("DELETE FROM `ox_app_registry` WHERE `org_id` =1 AND `app_id` IN(SELECT id from ox_app WHERE uuid = '$uniqueIdString');");
    	$this->addSql("ALTER TABLE `ox_privilege` CHANGE `app_id` `app_id` INT(32)  NULL;");
    	$this->addSql("ALTER TABLE `ox_role_privilege` CHANGE `app_id` `app_id` INT(32)  NULL;");
    	$this->addSql("UPDATE `ox_privilege` SET `permission_allowed` = 15 , `app_id` = NULL WHERE `name` LIKE 'MANAGE_USER';");
    	$this->addSql("UPDATE `ox_privilege` SET `permission_allowed`= 15,`app_id`= NULL WHERE `name` LIKE 'MANAGE_PROJECT';");
    	$this->addSql("UPDATE `ox_privilege` SET `app_id`= NULL WHERE `name` LIKE 'MANAGE_ANNOUNCEMENT';");
    	$this->addSql("UPDATE `ox_privilege` SET `app_id`= NULL WHERE `name` LIKE 'MANAGE_ROLE';");
    	$this->addSql("UPDATE `ox_privilege` SET `app_id`= NULL WHERE `name` LIKE 'MANAGE_GROUP';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`= NULL WHERE `name` LIKE 'MANAGE_ORGANIZATION';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`= NULL WHERE `name` LIKE 'MANAGE_APP';");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`= NULL,org_id = 0 WHERE `privilege_name` LIKE 'MANAGE_ANNOUNCEMENT' AND `role_id` = 1;");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`= NULL,org_id = 0 WHERE `privilege_name` LIKE 'MANAGE_APP' AND `role_id` = 1;");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`= NULL,org_id = 0 WHERE `privilege_name` LIKE 'MANAGE_ROLE' AND `role_id` = 1;");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`= NULL,org_id = 0 WHERE `privilege_name` LIKE 'MANAGE_GROUP' AND `role_id` = 1;");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`= NULL,org_id = 0 WHERE `privilege_name` LIKE 'MANAGE_ORGANIZATION' AND `role_id` = 1;");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`= NULL,org_id = 0 WHERE `privilege_name` LIKE 'MANAGE_USER' AND `role_id` = 1;");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`= NULL,org_id = 0 WHERE `privilege_name` LIKE 'MANAGE_PROJECT' AND `role_id` = 1;");
        $this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'Announcement' AND `category` LIKE 'EXAMPLE_CATEGORY';");
        $this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'App Builder' AND `category` LIKE 'EXAMPLE_CATEGORY';");
        $this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'User' AND `category` LIKE 'EXAMPLE_CATEGORY';");
        $this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'Project' AND `category` LIKE 'EXAMPLE_CATEGORY';");
        $this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'Group' AND `category` LIKE 'EXAMPLE_CATEGORY';");
        $this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'Role' AND `category` LIKE 'EXAMPLE_CATEGORY';");
        $this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'Organization' AND `category` LIKE 'EXAMPLE_CATEGORY';");
    }
}
