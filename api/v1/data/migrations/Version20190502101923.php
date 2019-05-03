<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190502101923 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) VALUES ('MANAGE_MYAPP',3,NULL,'0fc011f2-00ab-42cc-9de5-747ac6f47a2d');");
    	$this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) VALUES ('MANAGE_MYAPP',3,1,'0fc011f2-00ab-42cc-9de5-747ac6f47a2d');");
		$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (1,'MANAGE_MYAPP',3,NULL,'0fc011f2-00ab-42cc-9de5-747ac6f47a2d');");
		$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_MYAPP',3,1,'0fc011f2-00ab-42cc-9de5-747ac6f47a2d');");

		$this->addSql("UPDATE `ox_role_privilege` SET role_id = 1 WHERE org_id IS NULL AND role_id = 4");
		$this->addSql("UPDATE `ox_role_privilege` SET role_id = 2 WHERE org_id IS NULL AND role_id = 5");
		$this->addSql("UPDATE `ox_role_privilege` SET role_id = 3 WHERE org_id IS NULL AND role_id = 6");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM `ox_privilege` WHERE name = 'MANAGE_MYAPP'");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_MYAPP'");
    	$this->addSql("UPDATE `ox_role_privilege` SET role_id = 4 WHERE org_id IS NULL AND role_id = 1");
		$this->addSql("UPDATE `ox_role_privilege` SET role_id = 5 WHERE org_id IS NULL AND role_id = 2");
		$this->addSql("UPDATE `ox_role_privilege` SET role_id = 6 WHERE org_id IS NULL AND role_id = 3");
    }
}
