<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418083102 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) VALUES ('MANAGE_MLET',3,NULL,NULL);");
    	$this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) VALUES ('MANAGE_MLET',3,1,NULL);");
		$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (1,'MANAGE_MLET',3,1,NULL);");
		$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (2,'MANAGE_MLET',1,1,NULL);");
		$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (3,'MANAGE_MLET',1,1,NULL);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM `ox_privilege` WHERE name = 'MANAGE_MLET'");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_MLET'");
    }
}
