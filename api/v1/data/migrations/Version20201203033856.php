<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203033856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Esign document table ';
    }

    public function up(Schema $schema) : void
    {
       $sql = "CREATE TABLE ox_esign_document ( 
       `id` Int( 11 ) AUTO_INCREMENT NOT NULL,
       `uuid` VarChar( 50 ) NOT NULL,
       `ref_id` VarChar( 100 ) NOT NULL,
       `doc_id` VarChar( 100 ) NULL,
       `status` VarChar( 50 ) NOT NULL DEFAULT 'IN_PROGRESS',
       `created_by` INT(32) NOT NULL,
       `date_created` DATETIME NOT NULL,
       PRIMARY KEY ( `id` ),
       FOREIGN KEY (`created_by`) REFERENCES ox_user(`id`) )
       ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
       $this->addSql($sql);
   }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_esign_document");
    }

}
