<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401094401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding permissions to Mail Admin';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_role_privilege INNER JOIN ox_role on ox_role.id = ox_role_privilege.role_id SET ox_role_privilege.permission = 3 WHERE ox_role_privilege.privilege_name = 'MANAGE_TASKADMIN' AND ox_role.name = 'ADMIN'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_role_privilege INNER JOIN ox_role on ox_role.id = ox_role_privilege.role_id SET ox_role_privilege.permission = 1 WHERE ox_role_privilege.privilege_name = 'MANAGE_TASKADMIN' AND ox_role.name = 'ADMIN'");
    }
}
