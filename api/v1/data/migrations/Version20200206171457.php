<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200206171457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Fix Issues in Privileges';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //Fix Privileges for GROUPS
        $this->addSql("UPDATE ox_privilege SET name = 'MANAGE_GROUP' WHERE name = 'MANAGE_GROUP_WRITE'");
        $this->addSql("UPDATE ox_role_privilege SET privilege_name = 'MANAGE_GROUP' WHERE privilege_name = 'MANAGE_GROUP_WRITE'");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_ERROR',3);");

        //Fix Privileges For Admin App Error Log Viewing
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_ERROR',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (4, 'MANAGE_ERROR',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (7, 'MANAGE_ERROR',3);");

        //Fix Privileges For Admin App CRM Access
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_CRMADMIN' ");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_CRMADMIN',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_CRMADMIN',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (4, 'MANAGE_CRMADMIN',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (7, 'MANAGE_CRMADMIN',3);");

        //Fix Privileges for Announcements
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_ANNOUNCEMENT' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_ANNOUNCEMENT' AND role_id=4");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_ANNOUNCEMENT' AND role_id=2");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_ANNOUNCEMENT' AND role_id=8");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_ANNOUNCEMENT',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_ANNOUNCEMENT',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (4, 'MANAGE_ANNOUNCEMENT',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (7, 'MANAGE_ANNOUNCEMENT',3);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (3, 'MANAGE_ANNOUNCEMENT',1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (6, 'MANAGE_ANNOUNCEMENT',1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (9, 'MANAGE_ANNOUNCEMENT',1);");

        //Fix Privileges for User Creation
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_USER' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_USER'");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_USER',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,app_id) VALUES (1,'MANAGE_USER',15,1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,app_id) VALUES (4,'MANAGE_USER',15,1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,app_id) VALUES (7,'MANAGE_USER',15,1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) VALUES (3,'MANAGE_USER',1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) VALUES (6,'MANAGE_USER',1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) VALUES (9,'MANAGE_USER',1);");

        //Fix Privileges for Org Creation
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_ORGANIZATION' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_ORGANIZATION'");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_ORGANIZATION',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,app_id) VALUES (1,'MANAGE_ORGANIZATION',15,1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,app_id) VALUES (4,'MANAGE_ORGANIZATION',15,1);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_privilege SET name = 'MANAGE_GROUP_WRITE' WHERE name = 'MANAGE_GROUP'");
        $this->addSql("UPDATE ox_role_privilege SET privilege_name = 'MANAGE_GROUP_WRITE' WHERE privilege_name in ('MANAGE_GROUP')");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_ERROR' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_ERROR'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_CRM' ");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_CRM'");
    }
}
