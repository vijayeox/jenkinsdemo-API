<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412160024 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_PROSPECTRESEARCH',1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_PROSPECTRESEARCH',1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (2, 'MANAGE_PROSPECTRESEARCH',1);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (3, 'MANAGE_PROSPECTRESEARCH',1);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE ox_subscriber");
    	$this->addSql("DELETE FROM `ox_privilege` WHERE `name` LIKE 'MANAGE_PROSPECTRESEARCH' ");
		$this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_PROSPECTRESEARCH'");
    }
}
