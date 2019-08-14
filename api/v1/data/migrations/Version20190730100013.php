<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190730100013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_GROUP_WRITE',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_GROUP_WRITE',15);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
