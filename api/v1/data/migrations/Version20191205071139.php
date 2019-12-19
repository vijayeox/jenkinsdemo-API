<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191205071139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `ox_role_privilege` DROP FOREIGN KEY roleid");
        $this->addSql("ALTER TABLE `ox_role_privilege` DROP INDEX `role_privilege`;");
        $this->addSql("ALTER TABLE `ox_role_privilege` ADD UNIQUE `role_privilege` (`role_id`, `privilege_name`,`org_id`, `app_id`);");
        $this->addSql("ALTER TABLE `ox_role_privilege` ADD CONSTRAINT roleid FOREIGN KEY (role_id) REFERENCES ox_role(id)");
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
