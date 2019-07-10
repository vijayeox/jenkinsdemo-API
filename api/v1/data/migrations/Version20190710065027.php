<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190710065027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding roles and privileges for 2nd Organization';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT into ox_role (name, description, org_id, is_system_role, uuid) SELECT name, description, 2, 1, uuid() from ox_role where org_id is null;"); 
        $this->addSql("INSERT into ox_role_privilege (role_id, privilege_name, permission, app_id) VALUES (1, 'MANAGE_MYORG', 3, 1);"); 
        $this->addSql("INSERT into ox_role_privilege (role_id, privilege_name, permission, org_id, app_id) VALUES (5, 'MANAGE_MYORG', 3, 1, 1);"); 
        $this->addSql("INSERT into ox_role_privilege (role_id, privilege_name, permission, org_id, app_id) SELECT oro.id, privilege_name, permission, 2, app_id from ox_role oro inner join (select r.name, orp.* from ox_role as r inner join ox_role_privilege orp on r.id = orp.role_id and r.org_id is null) as rp on oro.name = rp.name where oro.org_id =2;"); 
        $this->addSql("INSERT into ox_user_role (user_id, role_id) select 5, id from ox_role where org_id = 2 and name = 'ADMIN';");
        $this->addSql("UPDATE `ox_organization` SET `contactid` = 1, `country` = 'US' where id = 1;");
		
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
