<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201222095551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE ox_widget_target ADD group_value varchar(100) NULL;');
        $this->addSql('ALTER TABLE ox_widget_target ADD CONSTRAINT ox_widget_target_FK FOREIGN KEY (widget_id) REFERENCES ox_widget(id);');
        $this->addSql('ALTER TABLE ox_widget_target ADD CONSTRAINT ox_widget_target_FK_1 FOREIGN KEY (target_id) REFERENCES ox_target(id);');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
