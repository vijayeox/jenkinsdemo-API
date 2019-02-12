<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211130632 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `ox_app_registry` (`id`, `org_id`, `app_id`) VALUES ('1', '1', '1');");
        $this->addSql("INSERT INTO `ox_app_registry` (`id`, `org_id`, `app_id`) VALUES ('2', '1', '2');");
        
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`, `privilege_name`, `permission`, `org_id`, `app_id`) VALUES ('1', 'MANAGE_APP', '15', '1', '1');");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`, `privilege_name`, `permission`, `org_id`, `app_id`) VALUES ('2', 'MANAGE_APP', '15', '1', '1');");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`, `privilege_name`, `permission`, `org_id`, `app_id`) VALUES ('3', 'MANAGE_APP', '15', '1', '1');");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
