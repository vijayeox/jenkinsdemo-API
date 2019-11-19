<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190906073338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app` MODIFY isdefault BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_app` MODIFY status BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_app` ADD CONSTRAINT app_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_app` ADD CONSTRAINT app_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");

        $this->addSql("ALTER TABLE `ox_workflow_instance` ADD CONSTRAINT workflow_instance_references_workflow FOREIGN KEY (workflow_id) REFERENCES ox_workflow(id)");
        $this->addSql("ALTER TABLE `ox_workflow_instance` ADD CONSTRAINT workflow_instance_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_workflow_instance` ADD CONSTRAINT workflow_instance_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_workflow_instance` ADD CONSTRAINT workflow_instance_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");

        $this->addSql("ALTER TABLE `ox_workflow` ADD CONSTRAINT workflow_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_workflow` ADD CONSTRAINT workflow_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_workflow` ADD CONSTRAINT workflow_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");

        $this->addSql("ALTER TABLE `ox_activity_instance` ADD CONSTRAINT activity_instance_references_form FOREIGN KEY (form_id) REFERENCES ox_form(id)");
        $this->addSql("ALTER TABLE `ox_activity_instance` ADD CONSTRAINT activity_instance_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        // $this->addSql("ALTER TABLE `ox_activity_instance` ADD CONSTRAINT activity_instance_references_group FOREIGN KEY (group_id) REFERENCES ox_group(id)");
        $this->addSql("ALTER TABLE `ox_activity_instance` ADD CONSTRAINT activity_instance_references_activity FOREIGN KEY (activity_id) REFERENCES ox_activity(id)");

        $this->addSql("ALTER TABLE `ox_activity` ADD CONSTRAINT activity_references_app FOREIGN KEY (app_id) REFERENCES ox_app(id)");
        $this->addSql("ALTER TABLE `ox_activity` ADD CONSTRAINT activity_references_workflow FOREIGN KEY (workflow_id) REFERENCES ox_workflow(id)");
        $this->addSql("ALTER TABLE `ox_activity` ADD CONSTRAINT activity_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_activity` ADD CONSTRAINT activity_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");

        $this->addSql("ALTER TABLE `ox_form` ADD CONSTRAINT form_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_form` ADD CONSTRAINT form_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");

        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_file FOREIGN KEY (fileid) REFERENCES ox_file(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` MODIFY fieldid INT(32) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_field FOREIGN KEY (fieldid) REFERENCES ox_field(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_org FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD CONSTRAINT file_attribute_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");

        $this->addSql("ALTER TABLE `ox_field` ADD CONSTRAINT field_references_workflow FOREIGN KEY (workflow_id) REFERENCES ox_workflow(id)");
        $this->addSql("ALTER TABLE `ox_field` ADD CONSTRAINT field_references_user FOREIGN KEY (created_by) REFERENCES ox_user(id)");
        $this->addSql("ALTER TABLE `ox_field` ADD CONSTRAINT field_references_user_modified FOREIGN KEY (modified_by) REFERENCES ox_user(id)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_app` MODIFY isdefault BOOLEAN");
        $this->addSql("ALTER TABLE `ox_app` MODIFY status BOOLEAN");
        $this->addSql("ALTER TABLE `ox_app` DROP FOREIGN KEY app_references_user");
        $this->addSql("ALTER TABLE `ox_app` DROP FOREIGN KEY app_references_user_modified");

        $this->addSql("ALTER TABLE `ox_workflow_instance` DROP FOREIGN KEY workflow_instance_references_workflow");
        $this->addSql("ALTER TABLE `ox_workflow_instance` DROP FOREIGN KEY workflow_instance_references_org");
        $this->addSql("ALTER TABLE `ox_workflow_instance` DROP FOREIGN KEY workflow_instance_references_user");
        $this->addSql("ALTER TABLE `ox_workflow_instance` DROP FOREIGN KEY workflow_instance_references_user_modified");

        $this->addSql("ALTER TABLE `ox_workflow` DROP FOREIGN KEY workflow_references_org");
        $this->addSql("ALTER TABLE `ox_workflow` DROP FOREIGN KEY workflow_references_user");
        $this->addSql("ALTER TABLE `ox_workflow` DROP FOREIGN KEY workflow_references_user_modified");

        $this->addSql("ALTER TABLE `ox_activity_instance` DROP FOREIGN KEY activity_instance_references_form");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP FOREIGN KEY activity_instance_references_org");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP FOREIGN KEY activity_instance_references_group");
        $this->addSql("ALTER TABLE `ox_activity_instance` DROP FOREIGN KEY activity_instance_references_activity");

        $this->addSql("ALTER TABLE `ox_activity` DROP FOREIGN KEY activity_references_app");
        $this->addSql("ALTER TABLE `ox_activity` DROP FOREIGN KEY activity_references_workflow");
        $this->addSql("ALTER TABLE `ox_activity` DROP FOREIGN KEY activity_references_user");
        $this->addSql("ALTER TABLE `ox_activity` DROP FOREIGN KEY activity_references_user_modified");

        $this->addSql("ALTER TABLE `ox_form` DROP FOREIGN KEY form_references_user");
        $this->addSql("ALTER TABLE `ox_form` DROP FOREIGN KEY form_references_user_modified");

        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_file");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_field");
        $this->addSql("ALTER TABLE `ox_file_attribute` MODIFY fieldid VARCHAR(250) NOT NULL");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_org");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_user");
        $this->addSql("ALTER TABLE `ox_file_attribute` DROP FOREIGN KEY file_attribute_references_user_modified");

        $this->addSql("ALTER TABLE `ox_field` DROP FOREIGN KEY field_references_workflow");
        $this->addSql("ALTER TABLE `ox_field` DROP FOREIGN KEY field_references_user");
        $this->addSql("ALTER TABLE `ox_field` DROP FOREIGN KEY field_references_user_modified");
    }
}
