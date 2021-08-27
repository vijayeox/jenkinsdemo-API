<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200810105338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding ox_file_documents table';
    }

    public function up(Schema $schema) : void
    {
        $sql = "CREATE TABLE `ox_file_document` ( 
                `id` Int( 32 ) AUTO_INCREMENT NOT NULL,
                `file_id` Int( 64 ) NOT NULL,
                `field_id` Int( 32 ) NOT NULL,
                `sequence` INT(11) NULL,
                `field_value` Text NULL,
                `org_id` Int( 11 ) NOT NULL,
                `created_by` Int( 32 ) NOT NULL DEFAULT 1,
                `modified_by` Int( 32 ) NULL,
                `date_created` DateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `date_modified` DateTime NULL,
                PRIMARY KEY ( `id` ))
                ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
        $this->addSql($sql);    
        $this->addSql("ALTER TABLE `ox_file_document` ADD CONSTRAINT FOREIGN KEY (`file_id`) REFERENCES ox_file(`id`);");
        $this->addSql("ALTER TABLE `ox_file_document` ADD CONSTRAINT FOREIGN KEY (`field_id`) REFERENCES ox_field(`id`);");
        $this->addSql("ALTER TABLE `ox_file_document` ADD CONSTRAINT FOREIGN KEY (`org_id`) REFERENCES ox_organization(`id`);");
        $this->addSql("ALTER TABLE `ox_file_document` ADD CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`);");
        $this->addSql("ALTER TABLE `ox_file_document` ADD CONSTRAINT FOREIGN KEY (`modified_by`) REFERENCES ox_user(`id`);");
        $this->addSql("ALTER TABLE  ox_file_document ADD INDEX ix_sequence (sequence)");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
