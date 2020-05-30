<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527211306 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_file_attachment` (
            `id` int(32) AUTO_INCREMENT NOT NULL,
            `uuid` varchar(250) NOT NULL,
            `name` varchar(1024) NOT NULL,
            `originalName` varchar(1024) NOT NULL,
            `extension` varchar(32) NOT NULL,
            `type` varchar(100) NOT NULL,
            `path` varchar(300)  NULL,
            `url` varchar(512) NULL,
            `created_id` int(32) NOT NULL,
            `org_id` int(32) NOT NULL,
            `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY ( `id` )
        ) ENGINE=InnoDB AUTO_INCREMENT=0  DEFAULT CHARSET=utf8;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_file_attachment");
    }
}
