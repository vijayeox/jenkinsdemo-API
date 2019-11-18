<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190705125232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Table alterations';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `dashboard` DROP COLUMN `layout_json`");
        $this->addSql("ALTER TABLE `widget_dashboard_mapper` CHANGE dimensions dimensions TEXT");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `dashboard` ADD column `layout_json` TEXT");        
        $this->addSql("ALTER TABLE `widget_dashboard_mapper` CHANGE dimensions dimensions varchar(100)");
    }
}
