<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190326104104 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql("DROP TRIGGER IF EXISTS `before_insert_ox_user`");
    	$this->addSql("CREATE TRIGGER before_insert_ox_user BEFORE INSERT ON ox_user FOR EACH ROW SET NEW.name = CONCAT(NEW.firstname, ' ', NEW.lastname); IF(NEW.uuid IS NULL OR NEW.uuid = '') THEN SET NEW.uuid = uuid(); END IF;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TRIGGER IF EXISTS `before_insert_ox_user`");
    }
}
