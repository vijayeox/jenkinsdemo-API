<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628045446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Permissions to access Apis';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `ox_app` (`name`,`uuid`,`type`,`isdefault`,`category`,`created_by`,`status`,`start_options`) VALUES ('Analytics','".Uuid::uuid4()->toString()."','1',0,'organization',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}')");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_DATASOURCE',3,(SELECT `id` FROM ox_app WHERE name = 'Analytics'));");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_DATASOURCE',3 FROM ox_role as ro WHERE ro.name = 'ADMIN' OR ro.name = 'MANAGER';");
        $this->addSql("INSERT INTO ox_role_privilege (role_id, privilege_name, permission) SELECT ro.id, 'MANAGE_DATASOURCE', 1 FROM ox_role as ro WHERE ro.name = 'EMPLOYEE';");
        $this->addSql("UPDATE ox_role_privilege, ox_app set ox_role_privilege.app_id = ox_app.id where privilege_name = 'MANAGE_DATASOURCE' and ox_app.name = 'ANALYTICS'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_DATASOURCE'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_DATASOURCE'");
        $this->addSql("DELETE FROM `ox_app` WHERE `name`= 'ANALYTICS'");
    }
}
