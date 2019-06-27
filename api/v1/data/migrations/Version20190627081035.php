<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190627081035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Changes to ox_user_manager';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_user_manager ADD FOREIGN KEY (user_id) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE ox_user_manager ADD FOREIGN KEY (manager_id) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE ox_user_manager ADD FOREIGN KEY (created_id) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE ox_user_manager MODIFY date_modified DATETIME NULL");
        $this->addSql("ALTER TABLE ox_user_manager MODIFY modified_id INT(11) NULL");
        $this->addSql("ALTER TABLE ox_user_manager ADD FOREIGN KEY (`modified_id`) REFERENCES ox_user(id)");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
