<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190429050535 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_privilege` SET `app_id` = (SELECT id from ox_app WHERE name LIKE 'Admin') WHERE `name` in ('MANAGE_ANNOUNCEMENT','MANAGE_GROUP','MANAGE_ORGANIZATION','MANAGE_USER','MANAGE_PROJECT','MANAGE_ROLE','MANAGE_APP')");
    	$this->addSql("UPDATE `ox_role_privilege` SET `app_id` = (SELECT id from ox_app WHERE name LIKE 'Admin') WHERE `privilege_name` in ('MANAGE_ANNOUNCEMENT','MANAGE_GROUP','MANAGE_ORGANIZATION','MANAGE_USER','MANAGE_PROJECT','MANAGE_ROLE') AND `role_id`= 1");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	
    }
}
