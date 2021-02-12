<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212081740 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Esign Document Path to update post Sign';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE `ox_esign_document` ADD COLUMN `docPath` VARCHAR(1000) NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_role` DROP COLUMN `app_id`");
    }
}
