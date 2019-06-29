<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190628113504 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_privilege SET permission_allowed = 3 WHERE name = 'MANAGE_GROUP'");
        $this->addSql("UPDATE ox_role_privilege SET permission = 3 WHERE privilege_name in ('MANAGE_GROUP','MANAGE_PROJECT')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_privilege SET permission_allowed = 15 WHERE name = 'MANAGE_GROUP'");
        $this->addSql("UPDATE ox_role_privilege SET permission = 15 WHERE privilege_name in ('MANAGE_GROUP','MANAGE_PROJECT')");
    }
}
