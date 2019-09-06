<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830110146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Updating default value for Analytics';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_datasource` MODIFY isdeleted BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_query` MODIFY isdeleted BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_query` MODIFY ispublic BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_visualization` MODIFY isdeleted BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_widget` MODIFY isdeleted BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_widget` MODIFY ispublic BOOLEAN NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE `ox_widget` DROP COLUMN data");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_datasource` MODIFY isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE `ox_query` MODIFY isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE `ox_query` MODIFY ispublic BOOLEAN");
        $this->addSql("ALTER TABLE `ox_visualization` MODIFY isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE `ox_widget` MODIFY isdeleted BOOLEAN");
        $this->addSql("ALTER TABLE `ox_widget` MODIFY ispublic BOOLEAN");
        $this->addSql("ALTER TABLE `ox_widget` ADD data TEXT");
    }
}
