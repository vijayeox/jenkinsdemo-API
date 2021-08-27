<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191226090218 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_wf_user_identifier 
            ADD COLUMN app_id INT(32),
            ADD COLUMN org_id INT(32)");
        $this->addSql("UPDATE ox_wf_user_identifier ui 
            JOIN ox_workflow_instance wi on wi.id = ui.workflow_instance_id 
            SET ui.app_id  = wi.app_id, ui.org_id = wi.org_id ");
        $this->addSql("ALTER TABLE ox_wf_user_identifier MODIFY app_id INT(32) NOT NULL,
                        MODIFY org_id INT(32) NOT NULL, 
                        ADD CONSTRAINT fk_user_identifier_app FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`),
                        ADD CONSTRAINT fk_user_identifier_org FOREIGN KEY (`org_id`) REFERENCES ox_organization(`id`)");
        $this->addSql("ALTER TABLE ox_wf_user_identifier DROP FOREIGN KEY `ox_wf_user_identifier_ibfk_2`, DROP COLUMN workflow_instance_id");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
