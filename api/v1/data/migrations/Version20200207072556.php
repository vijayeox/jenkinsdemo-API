<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200207072556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,app_id) VALUES (1,'MANAGE_PROJECT',3,1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,org_id,app_id) VALUES (7,'MANAGE_PROJECT',3,1,1);");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` = 'MANAGE_ORGANIZATION' AND role_id=1 AND org_id is null");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission,app_id) VALUES (1,'MANAGE_ROLE',3,1);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
