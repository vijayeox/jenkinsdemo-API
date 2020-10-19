<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201015143936 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove unused Privilege';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE from ox_role_privilege where privilege_name= 'MANAGE_MYAPP'");
        $this->addSql("DELETE from ox_privilege where name= 'MANAGE_MYAPP'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) SELECT 'MANAGE_MYAPP',3,NULL,id from ox_app WHERE name LIKE 'AppBuilder';");
    	$this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) SELECT 'MANAGE_MYAPP',3,1,id from ox_app WHERE name LIKE 'AppBuilder';");
		$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) SELECT 1,'MANAGE_MYAPP',3,NULL,id from ox_app WHERE name LIKE 'AppBuilder';");
		$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) SELECT 4,'MANAGE_MYAPP',3,1,id from ox_app WHERE name LIKE 'AppBuilder';");
    }
}
