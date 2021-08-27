<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819003144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Widget Targets Relationship Table';
    }

    public function up(Schema $schema) : void
    {
        $sql = "CREATE TABLE `ox_widget_target` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `widget_id` int(11) DEFAULT NULL,
            `target_id` int(11) DEFAULT NULL,
            `trigger_query_id` int(11) DEFAULT NULL,
            `group_key` varchar(45) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";          
        $this->addSql($sql);  

    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TABLE ox_widget_target;");

    }
}
