<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190718125435 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO ox_privilege (`name`,`permission_allowed`,`app_id`) VALUES ('MANAGE_CRMADMIN',1,1)");
        $this->addSql("UPDATE ox_privilege SET app_id =  1 where name in ('MANAGE_EMAIL','MANAGE_TASK')");
        $this->addSql("INSERT INTO ox_role_privilege (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (4,'MANAGE_CRMADMIN',1,1,1)");
        $this->addSql("INSERT INTO ox_role_privilege (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (7,'MANAGE_CRMADMIN',1,2,1)");
        $this->addSql("UPDATE ox_role_privilege SET app_id = 1 where privilege_name in ('MANAGE_TASK','MANAGE_EMAIL') and role_id in (4,7)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM ox_privilege where name = 'MANAGE_CRMADMIN'");
        $this->addSql("UPDATE ox_privilege SET app_id = 10 where name = 'MANAGE_EMAIL'");
        $this->addSql("UPDATE ox_privilege SET app_id = 14 where name = 'MANAGE_TASK'");
        $this->addSql("DELETE FROM ox_role_privilege where privilege_name = 'MANAGE_CRMADMIN'");
        $this->addSql("UPDATE ox_role_privilege SET app_id = 1 where privilege_name in ('MANAGE_TASK','MANAGE_EMAIL') and role_id in (4,7)");
    }
}
