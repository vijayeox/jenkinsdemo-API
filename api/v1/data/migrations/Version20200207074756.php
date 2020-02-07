<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200207074756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("UPDATE ox_role_privilege SET org_id=1 WHERE privilege_name = 'MANAGE_ANNOUNCEMENT' AND role_id=4");
        $this->addSql("UPDATE ox_role_privilege SET org_id=2 WHERE privilege_name = 'MANAGE_ANNOUNCEMENT' AND role_id=7");
        $this->addSql("UPDATE ox_role_privilege SET org_id=1 WHERE privilege_name = 'MANAGE_ANNOUNCEMENT' AND role_id=6");
        $this->addSql("UPDATE ox_role_privilege SET org_id=2 WHERE privilege_name = 'MANAGE_ANNOUNCEMENT' AND role_id=9");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
