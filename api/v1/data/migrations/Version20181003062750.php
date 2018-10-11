<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181003062750 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `links` CHANGE `orgid` `org_id` INT(11) NOT NULL;");
        $this->addSql("ALTER TABLE `links` CHANGE `avatarid` `avatar_id` INT(11) NULL DEFAULT NULL;");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_BOOKMARK',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_BOOKMARK',15);");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE `links` CHANGE `org_id` `orgid` INT(11) NOT NULL;");
        $this->addSql("ALTER TABLE `links` CHANGE `avatar_id` `avatarid` INT(11) NULL DEFAULT NULL;");
    }

}