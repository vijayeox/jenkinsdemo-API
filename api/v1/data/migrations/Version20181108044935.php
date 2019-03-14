<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181108044935 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS`ox_user_manager` (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `user_id` INT NOT NULL,
		  `manager_id` INT NOT NULL,
		  `created_id` INT NOT NULL,
		  `modified_id` INT NOT NULL,
		  `date_created` DATETIME NOT NULL,
		  `date_modified` DATETIME NOT NULL,
		  PRIMARY KEY (`id`))");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_user_manager");

    }
}
