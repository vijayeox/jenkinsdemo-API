<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603172904 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("CREATE TABLE  IF NOT EXISTS `ox_app_menu` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `name` VARCHAR(250) NOT NULL , `parent_id` INT(64) NOT NULL, `icon` TEXT NOT NULL, `sequence` INT(64) NOT NULL, `app_id` INT(11) NOT NULL, `created_by` INT(32) NOT NULL DEFAULT '1', `modified_by` INT(32) , `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `date_modified`  DATETIME , PRIMARY KEY ( `id` ),FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_app_menu");
    }
}
