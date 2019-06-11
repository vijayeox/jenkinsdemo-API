<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181114043929 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_form` ADD `app_id` INT(11) NOT NULL AFTER `id`,ADD CONSTRAINT FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`);");
    	$this->addSql("ALTER TABLE `ox_field` ADD `app_id` INT(11) NOT NULL AFTER `form_id`,ADD CONSTRAINT FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`);");    	
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
		$this->addSql("ALTER TABLE `ox_form` DROP `app_id`;");
    	$this->addSql("ALTER TABLE `ox_field` DROP `app_id`;");
    }
}
