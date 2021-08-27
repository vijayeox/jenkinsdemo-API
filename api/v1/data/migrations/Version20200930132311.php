<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930132311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Drop no_fiter_override and add exclude_overrides column';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_widget DROP COLUMN no_filter_override");
        $this->addSql("ALTER TABLE ox_widget ADD COLUMN exclude_overrides  varchar(250) NULL");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_widget DROP COLUMN exclude_overrides");

    }
}
