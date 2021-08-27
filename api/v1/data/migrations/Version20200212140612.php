<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212140612 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Making components of Analytics global';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_privilege set app_id = null where name IN ('MANAGE_DATASOURCE','MANAGE_QUERY','MANAGE_VISUALIZATION','MANAGE_ANALYTICS_WIDGET','MANAGE_DASHBOARD')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_privilege set app_id = (SELECT id from ox_app where name = 'Analytics') where name IN ('MANAGE_DATASOURCE','MANAGE_QUERY','MANAGE_VISUALIZATION','MANAGE_ANALYTICS_WIDGET','MANAGE_DASHBOARD')");
    }
}
