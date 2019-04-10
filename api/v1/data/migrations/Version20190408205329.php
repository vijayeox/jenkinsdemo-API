<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190408205329 extends AbstractMigration
{
	public function up(Schema $schema) : void
	{
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql("INSERT INTO `ox_role` (`name`,`description`) VALUES ('ADMIN','Must have read,write,create and delete control');");
		$this->addSql("INSERT INTO `ox_role` (`name`,`description`) VALUES ('MANAGER','Must have read and write control');");
		$this->addSql("INSERT INTO `ox_role` (`name`,`description`) VALUES ('EMPLOYEE','Must have read control');");
	}

	public function down(Schema $schema) : void
	{
        // this down() migration is auto-generated, please modify it to your needs
		$this->addSql("DELETE FROM `ox_role` WHERE `org_id` IS NULL OR `org_id` = 0;");
	}
}
?>