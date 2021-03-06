<?php

namespace Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180717113006 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) : void {
        $sql = "CREATE TABLE fields ( 
                    `id` Int( 11 ) AUTO_INCREMENT NOT NULL,
                    `name` VarChar( 100 ) NOT NULL,
                    `text` VarChar( 400 ) NOT NULL,
                    `columnname` VarChar( 1000 ) NULL,
                    `helpertext` VarChar( 150 ) NULL,
                    `type` VarChar( 30 ) NULL,
                    `options` VarChar( 10000 ) NULL,
                    `color` VarChar( 1000 ) NULL,
                    `regexpvalidator` VarChar( 100 ) NULL,
                    `validationtext` VarChar( 250 ) NULL,
                    `specialvalidator` VarChar( 50 ) NULL,
                    `expression` VarChar( 1000 ) NULL,
                    `condition` VarChar( 250 ) NULL,
                    `premiumname` VarChar( 50 ) NULL,
                    `xflat_parameter` Int( 2 ) NOT NULL DEFAULT '0',
                    `esign_parameter` Int( 11 ) NOT NULL DEFAULT '0' COMMENT 'this field will be used in esign api',
                    `field_type` VarChar( 100 ) NOT NULL DEFAULT 'config',
                    `category` VarChar( 1000 ) NOT NULL DEFAULT '1',
                    PRIMARY KEY ( `id` ) )
                ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
        $this->addSql($sql);
        $this->addSql("ALTER TABLE fields ADD UNIQUE INDEX ix_name (name)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE fields");
    }

}
