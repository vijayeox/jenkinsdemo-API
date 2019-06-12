<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612111122 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission, org_id, app_id) values (4, 'MANAGE_APP',15, 1, 1);");

        $this->addSql("DROP TABLE fields;");
        $this->addSql("DROP TABLE email_setting_server");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
