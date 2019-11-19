<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191003145354 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove Workflow id from field table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_field` DROP FOREIGN KEY field_references_workflow");
        $this->addSql("ALTER TABLE `ox_field` DROP COLUMN workflow_id");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_field ADD COLUMN `workflow_id` INT(32) NULL ");
        $this->addSql("ALTER TABLE `ox_field` ADD CONSTRAINT field_references_workflow FOREIGN KEY (workflow_id) REFERENCES ox_workflow(id)");
    }
}
