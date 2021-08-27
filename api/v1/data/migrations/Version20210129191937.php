<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Oxzion\Utils\UuidUtil;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129191937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Timesheet Entry for applications';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'Timesheet'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_TIMESHEET'");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_TIMESHEET'");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Timesheet','Services','".UuidUtil::uuid()."',2);");
        $jsonObject = `{"autostart":"false","hidden":"false"}`;
        $this->addSql("INSERT INTO `ox_app_registry` (account_id,app_id,start_options) SELECT 1,id ,'".$jsonObject."' from ox_app where `name` LIKE 'Timesheet';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 1,'MANAGE_TIMESHEET',1,1,id from ox_app where name LIKE 'Timesheet';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 2,'MANAGE_TIMESHEET',1,1,id from ox_app where name LIKE 'Timesheet';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 3,'MANAGE_TIMESHEET',1,1,id from ox_app where name LIKE 'Timesheet';");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM ox_app_registry WHERE `app_id` in (select id from ox_app WHERE `name` = 'Timesheet')");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_TIMESHEET'");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_TIMESHEET'");
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'Timesheet'");
    }
}
