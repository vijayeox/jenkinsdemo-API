<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Oxzion\Utils\UuidUtil;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316131515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update Applications for Admin Access Issues';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_role_privilege set app_id=NULL where `privilege_name` LIKE '%MANAGE_USER%' and permission=1");
        $this->addSql("UPDATE ox_role_privilege set app_id=NULL,permission=1 where `privilege_name` LIKE '%MANAGE_USER%' and permission=0");
        $this->addSql("UPDATE ox_privilege set app_id=NULL where `name` LIKE '%MANAGE_USER%'");
        
        $sql = "SELECT ox_app.name,ox_app_registry.id from ox_app inner join ox_app_registry on ox_app_registry.app_id=ox_app.id where ox_app.name='OrgChart'";
        $result = $this->connection->executeQuery($sql)->fetchAll();
        $rows = "";
        if(count($result)>0){
            $rows = implode(",",array_column($result,'id'));
            $this->addSql("DELETE FROM `ox_app_registry` WHERE id in (".$rows.")");
        }
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'OrgChart'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_ORGCHART'");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_ORGCHART'");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('OrgChart','Organization','".UuidUtil::uuid()."',2);");
        $jsonObject = `{"autostart":"false","hidden":"false"}`;
        $this->addSql("INSERT INTO `ox_app_registry` (account_id,app_id,start_options) SELECT 1,id ,'".$jsonObject."' from ox_app where `name` LIKE 'OrgChart';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 1,'MANAGE_ORGCHART',1,1,id from ox_app where name LIKE 'OrgChart';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 2,'MANAGE_ORGCHART',1,1,id from ox_app where name LIKE 'OrgChart';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 3,'MANAGE_ORGCHART',1,1,id from ox_app where name LIKE 'OrgChart';");

        $sql = "SELECT ox_app.name,ox_app_registry.id from ox_app inner join ox_app_registry on ox_app_registry.app_id=ox_app.id where ox_app.name='Documents'";
        $result = $this->connection->executeQuery($sql)->fetchAll();
        $rows = "";
        if(count($result)>0){
            $rows = implode(",",array_column($result,'id'));
            $this->addSql("DELETE FROM `ox_app_registry` WHERE id in (".$rows.")");
        }
        $this->addSql("DELETE FROM ox_app WHERE `name` = 'Documents'");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_DOCUMENTS'");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_DOCUMENTS'");
        $this->addSql("INSERT INTO `ox_app` (name,category,uuid,type) VALUES ('Documents','Organization','".UuidUtil::uuid()."',2);");
        $jsonObject = `{"autostart":"false","hidden":"false"}`;
        $this->addSql("INSERT INTO `ox_app_registry` (account_id,app_id,start_options) SELECT 1,id ,'".$jsonObject."' from ox_app where `name` LIKE 'Documents';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 1,'MANAGE_DOCUMENTS',1,1,id from ox_app where name LIKE 'Documents';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 2,'MANAGE_DOCUMENTS',1,1,id from ox_app where name LIKE 'Documents';");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT 3,'MANAGE_DOCUMENTS',1,1,id from ox_app where name LIKE 'Documents';");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
