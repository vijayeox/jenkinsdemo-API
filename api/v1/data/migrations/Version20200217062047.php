<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200217062047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'making dashboard and widget more configurable';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_privilege set permission_allowed = 15 where name IN ('MANAGE_ANALYTICS_WIDGET','MANAGE_DASHBOARD')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_privilege set permission_allowed = 3 where name IN ('MANAGE_ANALYTICS_WIDGET','MANAGE_DASHBOARD')");
    }
}
