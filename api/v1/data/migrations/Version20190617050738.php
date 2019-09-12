<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190617050738 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_announcement` ADD COLUMN `uuid` VARCHAR(40) AFTER `id`");
    	$this->addSql("ALTER TABLE `ox_organization` ADD COLUMN `preferences` TEXT AFTER `contactid`");
    	$this->addSql('UPDATE ox_organization set preferences=\'{"currency":"INR","timezone":"Asia/Calcutta","dateformat":"dd/mm/yyyy"}\' where id = 1;');
        $this->addSql('UPDATE ox_organization set preferences=\'{"currency":"INR","timezone":"Asia/Calcutta","dateformat":"dd/mm/yyyy"}\' where id = 2;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_announcement` DROP COLUMN `uuid`");
		$this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `preferences`");
    }
}
