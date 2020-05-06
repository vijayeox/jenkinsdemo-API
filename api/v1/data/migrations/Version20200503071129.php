<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200503071129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE ox_service_task_instance (
            id INT auto_increment NOT NULL,
            name VARCHAR(256) NOT NULL,
            task_id VARCHAR(256) NOT NULL,
            start_data LONGTEXT NOT NULL,
            completion_data LONGTEXT NOT NULL,
            date_executed DATETIME DEFAULT NOW() NOT NULL,
            workflow_instance_id VARCHAR(256) NOT NULL,
            file_id varchar(256) NOT NULL,
                    PRIMARY KEY ( `id` ) )
                ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql("DROP TABLE ox_service_task_instance");
    }
}
