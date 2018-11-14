<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180905142629 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("CREATE TABLE ox_widget ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(250) NOT NULL,
            `defaultwidth` INT NOT NULL , 
            `defaultheight` INT NOT NULL , 
            `applicationguid` VARCHAR( 250 ) NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB");        

        $this->addSql("CREATE TABLE ox_screen ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(250) NOT NULL,
             PRIMARY KEY (`id`)) ENGINE = InnoDB");        

        $this->addSql("CREATE TABLE ox_screen_widget ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `userid` INT NOT NULL , 
            `widgetid` INT NOT NULL , 
            `screenid` INT NOT NULL , 
            `width` INT NOT NULL , 
            `height` INT NOT NULL , 
            `column` INT NOT NULL , 
            `row` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB");

        $this->addSql("CREATE TABLE ox_org_widget ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `widgetid` INT NOT NULL , 
            `orgid` INT NOT NULL , 
            `locked` INT NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE ox_widget");
        $this->addSql("DROP TABLE ox_screen");
        $this->addSql("DROP TABLE ox_screen_widget");
        $this->addSql("DROP TABLE ox_org_widget");

    }
}
