<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807080510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add sequence';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER table ox_file_attribute add column sequence INT(32)");
        $this->addSql("CREATE INDEX sequence_index on ox_file_attribute(sequence)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP INDEX sequence_index on ox_file_attribute");
        $this->addSql("ALTER table ox_file_attribute DROP column sequence");
    }
}
