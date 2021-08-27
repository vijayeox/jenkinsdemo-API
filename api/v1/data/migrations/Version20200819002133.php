<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819002133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Target Table';
    }

    public function up(Schema $schema) : void
    {
        $sql = "CREATE TABLE `ox_target` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` char(36) DEFAULT NULL,
            `type` tinyint(1) DEFAULT NULL,
            `period_type` varchar(45) DEFAULT NULL,
            `red_limit` decimal(10,0) DEFAULT NULL,
            `yellow_limit` decimal(10,0) DEFAULT NULL,
            `green_limit` decimal(10,0) DEFAULT NULL,
            `red_workflow_id` int(11) DEFAULT NULL,
            `yellow_workflow_id` int(11) DEFAULT NULL,
            `green_workflow_id` int(11) DEFAULT NULL,
            `trigger_after` decimal(10,0) DEFAULT NULL,
            `created_by` int(32) DEFAULT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `org_id` int(11) DEFAULT NULL,
            `version` int(11) DEFAULT NULL,
            `isdeleted` tinyint(1) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";          
        $this->addSql($sql);   

    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TABLE ox_target;");

    }
}
