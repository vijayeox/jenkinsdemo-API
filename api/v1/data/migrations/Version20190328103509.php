<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328103509 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_user_session` ( `id` INT(32) NOT NULL AUTO_INCREMENT, `user_id` int(64) NOT NULL , `org_id` INT(64) NOT NULL ,`data` TEXT NOT NULL , `date_modified`  DATETIME , PRIMARY KEY ( `id` ) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE ox_user_session");
    }
}
