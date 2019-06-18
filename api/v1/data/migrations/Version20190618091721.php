<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618091721 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql("ALTER TABLE `ox_project` ADD COLUMN `manager_id` INT(11) AFTER `org_id`");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_project` DROP COLUMN `manager_id`");
    }
}
