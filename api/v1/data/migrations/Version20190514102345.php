<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190514102345 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_project` ADD COLUMN  `uuid` varchar(128) AFTER `id`");
    	$this->addSql("ALTER TABLE `ox_project` ADD CONSTRAINT uniq_name UNIQUE (`name`,`org_id`)");
        $this->addSql("UPDATE `ox_user` SET uuid = '4fd99e8e-758f-11e9-b2d5-68ecc57cde45' WHERE id = 1");
        $this->addSql("UPDATE `ox_user` SET uuid = '4fd9ce37-758f-11e9-b2d5-68ecc57cde45' WHERE id = 2");
        $this->addSql("UPDATE `ox_user` SET uuid = '4fd9f04d-758f-11e9-b2d5-68ecc57cde45' WHERE id = 3");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
