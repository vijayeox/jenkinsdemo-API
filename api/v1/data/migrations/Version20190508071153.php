<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190508071153 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_group` MODIFY `name` varchar(200);");
    	$this->addSql("ALTER TABLE `ox_group` ADD CONSTRAINT uniq_name UNIQUE (`name`,`org_id`)");
    	$this->addSql("ALTER TABLE `ox_group` ADD COLUMN  `uuid` varchar(40) AFTER `id`");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_group` MODIFY `name` varchar(2000);");
    	$this->addSql("ALTER TABLE `ox_group` DROP COLUMN  `uuid`");
    }
}
