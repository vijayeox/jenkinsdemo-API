<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
$GLOBALS['uniqueId'] = uniqid();
$GLOBALS['uniqueIdString'] = (string)$GLOBALS['uniqueId']; 

final class Version20190225134407 extends AbstractMigration
{
	
	public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$uniqueIdString = $GLOBALS['uniqueIdString'];
    	$this->addSql("ALTER TABLE `ox_app_registry` CHANGE `app_id` `app_id` VARCHAR(200) NOT NULL;");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('Admin App','EXAMPLE_CATEGORY','$uniqueIdString');");
        $this->addSql("INSERT INTO `ox_app_registry` (org_id,app_id,start_options) VALUES (1,'$uniqueIdString','json_object');");
        $this->addSql("ALTER TABLE `ox_privilege` CHANGE `app_id` `app_id` VARCHAR(200) NOT NULL;");
        $this->addSql("ALTER TABLE `ox_role_privilege` CHANGE `app_id` `app_id` VARCHAR(200) NOT NULL;");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed`=3,`app_id`='$uniqueIdString' WHERE `name` LIKE 'MANAGE_USER';");
        $this->addSql("UPDATE `ox_privilege` SET `permission_allowed`=3,`app_id`='$uniqueIdString' WHERE `name` LIKE 'MANAGE_PROJECT';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`='$uniqueIdString' WHERE `name` LIKE 'MANAGE_ANNOUNCEMENT';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`='$uniqueIdString' WHERE `name` LIKE 'MANAGE_ROLE';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`='$uniqueIdString' WHERE `name` LIKE 'MANAGE_GROUP';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`='$uniqueIdString' WHERE `name` LIKE 'MANAGE_ORGANIZATION';");
        $this->addSql("UPDATE `ox_privilege` SET `app_id`='$uniqueIdString' WHERE `name` LIKE 'MANAGE_APP';");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_ANNOUNCEMENT' AND `role_id` = 1");
//        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_APP' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_ROLE' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_GROUP' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_ORGANIZATION' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_USER' AND `role_id` = 1");
        $this->addSql("UPDATE `ox_role_privilege` SET `app_id`='$uniqueIdString',org_id = 1 WHERE `privilege_name` LIKE 'MANAGE_PROJECT' AND `role_id` = 1");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('Announcement','EXAMPLE_CATEGORY','".uniqid()."');");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('App Builder','EXAMPLE_CATEGORY','".uniqid()."');");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('User','EXAMPLE_CATEGORY','".uniqid()."');");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('Project','EXAMPLE_CATEGORY','".uniqid()."');");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('Group','EXAMPLE_CATEGORY','".uniqid()."');");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('Role','EXAMPLE_CATEGORY','".uniqid()."');");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid) VALUES ('Organization','EXAMPLE_CATEGORY','".uniqid()."');");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$uniqueIdString = $GLOBALS['uniqueIdString'];
    	$this->addSql("ALTER TABLE `ox_app_registry` CHANGE `app_id` `app_id` INT(11) NOT NULL;");
    	$this->addSql("DELETE FROM `ox_app` WHERE `name` LIKE 'Admin App' AND `category` LIKE 'EXAMPLE_CATEGORY';");
    	$this->addSql("DELETE FROM `ox_app_registry` WHERE `org_id` =1 AND `app_id` = '$uniqueIdString';");
    	$this->addSql("ALTER TABLE `ox_privilege` CHANGE `app_id` `app_id` INT(32) NOT NULL;");
    	$this->addSql("ALTER TABLE `ox_role_privilege` CHANGE `app_id` `app_id` INT(32) NOT NULL;");
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
