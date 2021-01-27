<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014124223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $database = $this->connection->getDatabase();
        $sql = "SELECT count(column_name)
                    from information_schema.columns 
                    where table_schema =  '$database'
                      and table_name = 'ox_role_privilege'
                      and column_name = 'org_id'";
        $result = $this->connection->fetchArray($sql);
        $orgColumn = 'org_id';
        if(!$result[0]){
            $orgColumn = 'account_id';
        }

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed, app_id) values ('MANAGE_APPLICATION',3, NULL);");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed, app_id) values ('MANAGE_INSTALL_APP',3, NULL);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, $orgColumn, app_id) values (4, 'MANAGE_APPLICATION',3, 1, 1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, $orgColumn, app_id) values (6, 'MANAGE_INSTALL_APP',3, 1, 1);");

        if($result[0]){
            $this->addSql("ALTER TABLE `ox_user_role` ADD CONSTRAINT app_role FOREIGN KEY (role_id) REFERENCES ox_role(id)");
            $this->addSql("ALTER TABLE `ox_user_role` ADD CONSTRAINT app_user FOREIGN KEY (user_id) REFERENCES ox_user(id)");
        }

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM ox_privilege where privilege_name = 'MANAGE_APPLICATION'");
        $this->addSql("DELETE FROM ox_role_privilege where name = 'MANAGE_APPLICATION'");

        $this->addSql("ALTER TABLE `ox_user_role` DROP  FOREIGN KEY `app_role`;");
        $this->addSql("ALTER TABLE `ox_user_role` DROP  FOREIGN KEY `app_user`;");

    }
}
