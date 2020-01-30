<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208065945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_error_log ADD COLUMN app_id int(32) NULL DEFAULT NULL");
        $this->addSql("ALTER TABLE ox_error_log ADD CONSTRAINT FK_AppId FOREIGN KEY (`app_id`) REFERENCES ox_app(`id`)");
    }
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_error_log` DROP FOREIGN KEY FK_AppId");
        $this->addSql("ALTER TABLE `ox_error_log` DROP COLUMN `app_id`");
    }
}
