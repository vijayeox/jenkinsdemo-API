<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190201061546 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `ox_role_privilege` (`id`, `role_id`, `privilege_name`, `permission`, `org_id`, `app_id`) VALUES (NULL, '1', 'MANAGE_CONTACT', '15', NULL, NULL);");

        $this->addSql("INSERT INTO `ox_role_privilege` (`id`, `role_id`, `privilege_name`, `permission`, `org_id`, `app_id`) VALUES (NULL, '2', 'MANAGE_CONTACT', '8', NULL, NULL);");

        $this->addSql("INSERT INTO `ox_role_privilege` (`id`, `role_id`, `privilege_name`, `permission`, `org_id`, `app_id`) VALUES (NULL, '3', 'MANAGE_CONTACT', '7', NULL, NULL);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
