<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200207064636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Org ID to default Admins';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_role_privilege SET org_id = 1 WHERE privilege_name = 'MANAGE_ORGANIZATION' AND role_id=4");
        $this->addSql("UPDATE ox_role_privilege SET org_id = 1 WHERE privilege_name = 'MANAGE_USER' AND role_id=4");
        $this->addSql("UPDATE ox_role_privilege SET org_id = 2 WHERE privilege_name = 'MANAGE_USER' AND role_id=7");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
