<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190629052310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed, app_id) values ('MANAGE_MYORG',3, NULL);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, org_id, app_id) values (4, 'MANAGE_MYORG',3, 1, 1);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM ox_privilege where privilege_name = 'MANAGE_MYORG'");
        $this->addSql("DELETE FROM ox_role_privilege where name = 'MANAGE_MYORG'");
    }
}
