<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503102610 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_organization` ADD COLUMN  `uuid` varchar(40) AFTER `id`");
    	$this->addSql("UPDATE `ox_organization` SET `uuid` = '53012471-2863-4949-afb1-e69b0891c98a' WHERE id = 1");
    	$this->addSql("UPDATE `ox_organization` SET `uuid` = 'b0971de7-0387-48ea-8f29-5d3704d96a46' WHERE id = 2");        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `uuid`");
    }
}
