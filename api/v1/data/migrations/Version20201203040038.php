<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203040038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'creating ox_esign_document_signer';
    }

    public function up(Schema $schema) : void
    {
       $sql = "CREATE TABLE ox_esign_document_signer ( 
                    `id` Int( 11 ) AUTO_INCREMENT NOT NULL,
                    `esign_document_id` int(11) NOT NULL,
                    `status` VarChar( 100 ) NOT NULL DEFAULT 'IN_PROGRESS',
                    `email` VarChar( 200 ) NOT NULL,
                    `details` JSON NOT NULL,
                    `date_modified` DATETIME NULL,
                    PRIMARY KEY ( `id` ),
                    FOREIGN KEY (`esign_document_id`) REFERENCES ox_esign_document(`id`) )
                ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
        $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_esign_document_signer");
    }

}
