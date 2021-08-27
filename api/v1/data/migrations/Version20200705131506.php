<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200705131506 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_user_cache ADD column workflow_id INT(32), ADD CONSTRAINT `workflow_reference` FOREIGN KEY (`workflow_id`) REFERENCES ox_workflow(`id`) ");
        $this->addSql("ALTER TABLE ox_user_cache ADD column workflow_instance_id INT(32), ADD CONSTRAINT `workflow_instance_reference` FOREIGN KEY (`workflow_instance_id`) REFERENCES ox_workflow_instance(`id`) ");
        $this->addSql("ALTER TABLE ox_user_cache ADD column activity_instance_id INT(32), ADD CONSTRAINT `activity_instance_reference` FOREIGN KEY (`activity_instance_id`) REFERENCES ox_activity_instance(`id`) ");
        $this->addSql("ALTER TABLE ox_user_cache ADD column form_id INT(32), ADD CONSTRAINT `form_reference` FOREIGN KEY (`form_id`) REFERENCES ox_form(`id`) ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_error_log DROP CONSTRAINT workflow_reference");
        $this->addSql("ALTER TABLE ox_error_log DROP CONSTRAINT workflow_instance_reference");
        $this->addSql("ALTER TABLE ox_error_log DROP CONSTRAINT activity_instance_reference");
        $this->addSql("ALTER TABLE ox_error_log DROP CONSTRAINT form_reference");
        $this->addSql("ALTER TABLE ox_error_log DROP COLUMN workflow_id");
        $this->addSql("ALTER TABLE ox_error_log DROP COLUMN workflow_instance_id");
        $this->addSql("ALTER TABLE ox_error_log DROP COLUMN activity_instance_id");
        $this->addSql("ALTER TABLE ox_error_log DROP COLUMN form_id");
    }
}
