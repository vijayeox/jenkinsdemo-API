<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190617065501 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT IGNORE INTO `ox_app` (`name`,`uuid`,`type`,`isdefault`,`category`,`created_by`,`status`,`start_options`) VALUES ('TaskAdmin','".Uuid::uuid4()->getHex()."','1',0,'utilities',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}')");
        $this->addSql("INSERT IGNORE INTO `ox_app` (`name`,`uuid`,`type`,`isdefault`,`category`,`created_by`,`status`,`start_options`) VALUES ('Task','".Uuid::uuid4()->getHex()."','1',0,'organization',1,4,'{\"autostart\":\"false\",\"hidden\":\"false\"}')");
        $this->addSql("INSERT IGNORE INTO `ox_app_registry` (`org_id`,`app_id`,`date_created`) SELECT 1,id,now() from ox_app WHERE name LIKE 'TaskAdmin'");
        $this->addSql("INSERT IGNORE INTO `ox_app_registry` (`org_id`,`app_id`,`date_created`) SELECT 1,id,now() from ox_app WHERE name LIKE 'Task'");
        $this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`app_id`) SELECT 'MANAGE_TASK',1,id from ox_app WHERE name LIKE 'TaskAdmin'");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) SELECT ro.id,'MANAGE_TASK',1,ro.org_id,ap.id from ox_app ap,ox_role ro WHERE ap.name LIKE 'Task' AND ro.name LIKE 'admin'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE from ox_privilege WHERE `name` LIKE 'MANAGE_TASK'");
        $this->addSql("DELETE from ox_role_privilege WHERE `privilege_name` LIKE 'MANAGE_TASK'");
    	$this->addSql("DELETE FROM ox_app_registry WHERE `app_id` = (SELECT `id` from `ox_app` WHERE `name` LIKE 'TaskAdmin')");
    	$this->addSql("DELETE FROM ox_app WHERE `name` = 'TaskAdmin'");
    	$this->addSql("DELETE FROM ox_app_registry WHERE `app_id` = (SELECT `id` from `ox_app` WHERE `name` LIKE 'Task')");
    	$this->addSql("DELETE FROM ox_app WHERE `name` = 'Task'");
    }
}
