<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210305075119 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update User Flag for first time User';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_user` CHANGE COLUMN `policy_terms` `has_logged_in` int(11) null DEFAULT NULL;");
        $this->addSql('ALTER TABLE `ox_user` ADD COLUMN `verification_pending` TEXT DEFAULT NULL;');
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_TEAM'");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_TEAM',15,1);");
        $this->addSql("UPDATE `ox_role_privilege` SET `privilege_name`='MANAGE_TEAM' WHERE `privilege_name` LIKE 'MANAGE_GROUP'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_user` CHANGE COLUMN `has_logged_in` `policy_terms` int(11) null DEFAULT NULL;");
        $this->addSql('ALTER TABLE `ox_user` DROP COLUMN `verification_pending`;');
        $this->addSql("DELETE FROM `ox_privilege` WHERE `name` = 'MANAGE_GROUP'");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed,app_id) values ('MANAGE_GROUP',15,1);");
        $this->addSql("UPDATE `ox_role_privilege` SET `privilege_name`='MANAGE_GROUP' WHERE `privilege_name` LIKE 'MANAGE_TEAM'");
    }
}
