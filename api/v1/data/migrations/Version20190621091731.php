<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190621091731 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM ox_role_privilege WHERE privilege_name = 'MANAGE_CONTACT'");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	 $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, org_id, app_id) values (1, 'MANAGE_CONTACT',15, NULL, NULL);");
    	 $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, org_id, app_id) values (2, 'MANAGE_CONTACT',8, NULL, NULL);");
    	 $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, org_id, app_id) values (3, 'MANAGE_CONTACT',7, NULL, NULL);");
    	 $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, org_id, app_id) values (4, 'MANAGE_CONTACT',15, 1, NULL);");
    	 $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, org_id, app_id) values (5, 'MANAGE_CONTACT',8, 1, NULL);");
    }
}
