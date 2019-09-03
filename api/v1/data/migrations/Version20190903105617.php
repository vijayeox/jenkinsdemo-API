<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903105617 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'dashboard changes';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_dashboard` ADD `content` TEXT");
        $this->addSql("ALTER TABLE `ox_dashboard` MODIFY description varchar(512)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_dashboard` DROP COLUMN content");
        $this->addSql("ALTER TABLE `ox_dashboard` MODIFY description varchar(128)");
    }
}
