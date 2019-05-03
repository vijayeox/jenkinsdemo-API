<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430101311 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_app_registry` ADD CONSTRAINT uniq_id UNIQUE (`org_id`,`app_id`)");
    	$this->addSql("UPDATE `ox_role_privilege` SET  `org_id` = NULL WHERE org_id = 0");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_EMAIL' AND role_id in(2,3)");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (1,'MANAGE_EMAIL',1,1,'0b6f422a-64d9-45a5-8a22-992162845d86')");
    	$this->addSql("UPDATE `ox_role` SET `org_id` = NULL WHERE org_id in (1,2,3)");
    	$this->addSql("INSERT INTO `ox_role` (`id`,`name`,`description`,`org_id`) VALUES(4,'ADMIN','Must have read,write,create and delete control',1)");
    	$this->addSql("INSERT INTO `ox_role` (`id`,`name`,`description`,`org_id`) VALUES(5,'EMPLOYEE','Must have read control',1)");
    	$this->addSql("INSERT INTO `ox_role` (`id`,`name`,`description`,`org_id`) VALUES(6,'MANAGER','Must have read and write control',1)");
    	$this->addSql("UPDATE `ox_role_privilege` SET `role_id` = 4 WHERE `role_id` = 1");
    	$this->addSql("UPDATE `ox_role_privilege` SET `role_id` = 5 WHERE `role_id` = 2");
    	$this->addSql("UPDATE `ox_role_privilege` SET `role_id` = 6 WHERE `role_id` = 3");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_role_privilege` SET  `org_id` = 0 WHERE org_id = NULL");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (2,'MANAGE_EMAIL',1,1,'0b6f422a-64d9-45a5-8a22-992162845d86')");
    	$this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`) VALUES (3,'MANAGE_EMAIL',1,1,'0b6f422a-64d9-45a5-8a22-992162845d86')");
    	$this->addSql("DELETE FROM `ox_role_privilege` WHERE privilege_name = 'MANAGE_EMAIL' AND org_id = 1");
    	$this->addSql("UPDATE `ox_role` SET `org_id` = 1 WHERE org_id in (1,2,3)");
    	$this->addSql("DELETE FROM `ox_role` WHERE org_id = 1");
    	$this->addSql("UPDATE `ox_role_privilege` SET `role_id` = 1 WHERE `role_id` = 4");
    	$this->addSql("UPDATE `ox_role_privilege` SET `role_id` = 2 WHERE `role_id` = 5");
    	$this->addSql("UPDATE `ox_role_privilege` SET `role_id` = 3 WHERE `role_id` = 6");
    }
}
