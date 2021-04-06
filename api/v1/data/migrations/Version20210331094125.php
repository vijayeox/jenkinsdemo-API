<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331094125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $sql = "SELECT ox_role.account_id as account_id, ox_role.id as role_id
        from ox_role 
        left outer join ox_account on ox_account.id=ox_role.account_id 
        left outer join ox_role_privilege oxrp on oxrp.role_id = ox_role.id 
        and oxrp.privilege_name = 'MANAGE_APPBUILDER'
        where ox_role.name='Admin' and ox_role.app_id is NULL and oxrp.role_id is NULL";
        $result = $this->connection->executeQuery($sql)->fetchAll();
        if(count($result)>0){
            foreach($result as $row){
                $accountId = $row['account_id'] ? $row['account_id'] : "NULL";
                $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`account_id`,`app_id`) SELECT ".$row['role_id'].",'MANAGE_APPBUILDER',15,".$accountId .",id from ox_app WHERE name LIKE 'Admin';");
            }
        }

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
