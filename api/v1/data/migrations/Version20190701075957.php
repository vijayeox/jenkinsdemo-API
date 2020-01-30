<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701075957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Permissions to access APIs';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_QUERY',3,(SELECT `id` FROM ox_app WHERE name = 'Analytics'));");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_QUERY',3 FROM ox_role as ro WHERE ro.name = 'ADMIN' OR ro.name = 'MANAGER';");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_QUERY', 1 FROM ox_role as ro WHERE ro.name = 'EMPLOYEE';");
        $this->addSql("UPDATE ox_role_privilege, ox_app set ox_role_privilege.app_id = ox_app.id where privilege_name = 'MANAGE_QUERY' and ox_app.name = 'ANALYTICS'");

        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_VISUALIZATION',3,(SELECT `id` FROM ox_app WHERE name = 'Analytics'));");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_VISUALIZATION',3 FROM ox_role as ro WHERE ro.name = 'ADMIN' OR ro.name = 'MANAGER';");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_VISUALIZATION', 1 FROM ox_role as ro WHERE ro.name = 'EMPLOYEE';");
        $this->addSql("UPDATE ox_role_privilege, ox_app set ox_role_privilege.app_id = ox_app.id where privilege_name = 'MANAGE_VISUALIZATION' and ox_app.name = 'ANALYTICS'");

        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_ANALYTICS_WIDGET',3,(SELECT `id` FROM ox_app WHERE name = 'Analytics'));");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_ANALYTICS_WIDGET',3 FROM ox_role as ro WHERE ro.name = 'ADMIN' OR ro.name = 'MANAGER';");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_ANALYTICS_WIDGET', 1 FROM ox_role as ro WHERE ro.name = 'EMPLOYEE';");
        $this->addSql("UPDATE ox_role_privilege, ox_app set ox_role_privilege.app_id = ox_app.id where privilege_name = 'MANAGE_ANALYTICS_WIDGET' and ox_app.name = 'ANALYTICS'");

        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_DASHBOARD',3,(SELECT `id` FROM ox_app WHERE name = 'Analytics'));");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_DASHBOARD',3 FROM ox_role as ro WHERE ro.name = 'ADMIN' OR ro.name = 'MANAGER';");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_DASHBOARD', 1 FROM ox_role as ro WHERE ro.name = 'EMPLOYEE';");
        $this->addSql("UPDATE ox_role_privilege, ox_app set ox_role_privilege.app_id = ox_app.id where privilege_name = 'MANAGE_DASHBOARD' and ox_app.name = 'ANALYTICS'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_DATASOURCE'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_DATASOURCE'");

        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_VISUALIZATION'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_VISUALIZATION'");

        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_ANALYTICS_WIDGET'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_ANALYTICS_WIDGET'");

        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_DASHBOARD'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_DASHBOARD'");
    }
}
