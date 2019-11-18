<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190731095243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Changes to prevent Analytics from getting blacklisted';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `ox_app_registry`(`org_id`,`app_id`,`date_created`) SELECT 1,id,now() from ox_app WHERE name LIKE 'Analytics'");
        $this->addSql("UPDATE `ox_role_privilege` as ar SET ar.org_id = 1 where ar.role_id IN (Select id from ox_role where org_id = 1)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM ox_app_registry WHERE `app_id` = (SELECT `id` from `ox_app` WHERE `name` LIKE 'Analytics')");
    }
}
