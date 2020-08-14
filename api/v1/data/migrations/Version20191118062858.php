<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191118062858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_workflow_deployment` (`id` INT(32) NOT NULL AUTO_INCREMENT,
                        `workflow_id` INT(32) NOT NULL, `process_definition_id` TEXT NOT NULL,
                        `latest` INT(5) NULL DEFAULT '1', `date_created`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                        `form_id` INT(32) NULL,`created_by` INT(32) NOT NULL,
                         PRIMARY KEY (`id`),FOREIGN KEY (`form_id`) REFERENCES ox_form(`id`),
                         FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`), FOREIGN KEY (`workflow_id`) REFERENCES ox_workflow(`id`))ENGINE = InnoDB;");

        $this->addSql("INSERT INTO `ox_workflow_deployment` (`id`, `workflow_id`,`process_definition_id`,`form_id`,`created_by`,`date_created`,`latest`)
               SELECT `id`, `id`,`process_id`,`form_id`,`created_by`,`date_created`,0 FROM `ox_workflow`");

        $this->addSql("UPDATE `ox_workflow` SET `process_id` = SUBSTRING(`process_id`,1,LOCATE(':',`process_id`))");

        $this->addSql("UPDATE `ox_workflow_deployment` as wd INNER JOIN ( select max(id) as id,process_id from ox_workflow
                       group by process_id ) as w on w.process_id = SUBSTRING(wd.process_definition_id,1,LOCATE(':',wd.process_definition_id))
                       SET wd.workflow_id = w.id" );

        $this->addSql("UPDATE `ox_workflow_deployment` SET `latest` = 1 WHERE `id` = `workflow_id`");

        $this->addSql("ALTER TABLE ox_activity DROP FOREIGN KEY activity_references_workflow");
        $this->addSql("ALTER TABLE ox_activity DROP COLUMN `workflow_id`");
        $this->addSql("ALTER TABLE ox_activity ADD COLUMN `workflow_deployment_id` int(32) NOT NULL");
        $this->addSql("ALTER TABLE ox_activity ADD CONSTRAINT FOREIGN KEY (`workflow_deployment_id`) REFERENCES ox_workflow_deployment(`id`) ");
        $this->addSql ("ALTER TABLE ox_workflow_instance DROP FOREIGN KEY workflow_instance_references_workflow");
        $this->addSql ("ALTER TABLE ox_workflow_instance DROP INDEX workflow_instance_references_workflow");
        $this->addSql("ALTER TABLE ox_workflow_instance CHANGE COLUMN `workflow_id` `workflow_deployment_id` int(32) NOT NULL");
        $this->addSql("ALTER TABLE ox_workflow_instance ADD CONSTRAINT FOREIGN KEY (`workflow_deployment_id`) REFERENCES ox_workflow_deployment(`id`) ");

        $this->addSql("DELETE w FROM `ox_workflow` as w inner join ( 
                       select max(id) as id,process_id from ox_workflow group by process_id ) as wf
                       on w.process_id = wf.process_id
                       WHERE w.id != wf.id ");
        $this->addSql("ALTER TABLE `ox_workflow` DROP COLUMN `form_id`");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_workflow_deployment`;");

    }
}
