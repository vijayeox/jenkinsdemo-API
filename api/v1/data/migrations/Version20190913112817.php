<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190913112817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_payment` DROP COLUMN `app_id`");
        $this->addSql("ALTER TABLE `ox_payment` ADD COLUMN `app_id` INT(11) NOT NULL AFTER `id`,ADD FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`)");        
        $this->addSql("ALTER TABLE `ox_payment` MODIFY COLUMN `api_url` varchar(255) NOT NULL;");
        $this->addSql("ALTER TABLE `ox_payment` MODIFY COLUMN `payment_config` longtext NOT NULL;"); 
        $this->addSql("ALTER TABLE `ox_payment_trasaction` ADD COLUMN `data` longtext NOT NULL AFTER `file_id`");               
        $this->addSql("ALTER TABLE `ox_payment_trasaction` DROP COLUMN `file_id`");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
