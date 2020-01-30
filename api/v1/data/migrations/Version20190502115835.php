<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190502115835 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `ox_privilege` WHERE org_id = 1");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_ATTACHMENT',3,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_SCREEN',3,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (5,'MANAGE_SCREEN',1,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_WIDGET',3,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (5,'MANAGE_WIDGET',1,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_BOOKMARK',15,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_DOMAIN',15,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_CONTACT',15,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (5,'MANAGE_CONTACT',8,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_PRIVILEGE',3,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_FILE',15,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_FORM',15,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_ALERT',3,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (5,'MANAGE_ALERT',1,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_FIELD',15,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_COMMENT',15,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_SCREENWIDGET',3,1,NULL);");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (5,'MANAGE_SCREENWIDGET',3,1,NULL);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_ATTACHMENT' AND role_id = 4");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_SCREEN' AND role_id in (4,5)");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_WIDGET' AND role_id in (4,5)");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_BOOKMARK' AND role_id = 4");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_DOMAIN' AND role_id = 4");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_PROJECT' AND role_id = 1");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_CONTACT' AND role_id in (4,5)");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_PRIVILEGE' AND role_id = 4");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_FILE' AND role_id = 4");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_FORM' AND role_id = 4");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_ALERT' AND role_id in (4,5)");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_FIELD' AND role_id = 4");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_SCREENWIDGET' AND role_id in (4,5)");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_COMMENT' AND role_id = 4");
    }
}
