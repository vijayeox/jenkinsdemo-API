<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210729173732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Prehire initial schema';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_prehire` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(45) DEFAULT NULL,
            `user_id` int(11) DEFAULT NULL,
            `request_type` varchar(45) NOT NULL,
            `request` text,
            `implementation` varchar(45) NOT NULL,
            `date_created` datetime DEFAULT NULL,
            `date_modified` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`user_id`) REFERENCES ox_user(`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_PREHIRE',7,NULL);");
        $sql = "SELECT ox_account.id as account_id,ox_role.id as role_id from ox_account inner join ox_role on ox_account.id=ox_role.account_id where ox_role.name='Admin' and ox_role.app_id is NULL ";
        $result = $this->connection->executeQuery($sql)->fetchAll();
        if(count($result)>0){
            foreach($result as $row){
                $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT ".$row['role_id'].",'MANAGE_PREHIRE',7,".$row['account_id'].",id from ox_app WHERE name LIKE 'Admin';");
            }
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP table ox_prehire");
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_PREHIRE'");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_PREHIRE'");
    }
}
