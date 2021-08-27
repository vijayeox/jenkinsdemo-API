<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014060233 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app_menu` MODIFY parent_id VARCHAR(255)");
        $this->addSql("ALTER TABLE `ox_role_privilege` ADD CONSTRAINT roleid FOREIGN KEY (role_id) REFERENCES ox_role(id)");
        $this->addSql("ALTER TABLE `ox_app_menu` ADD CONSTRAINT privilegeid FOREIGN KEY (privilege_id) REFERENCES ox_privilege(id)");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_role_privilege` DROP FOREIGN KEY roleid");
        $this->addSql("ALTER TABLE `ox_app_menu` DROP FOREIGN KEY privilegeid");
    }
}
