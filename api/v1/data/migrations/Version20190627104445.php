<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190627104445 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Workflow table cleanup';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_workflow MODIFY process_keys TEXT NULL");
        $this->addSql("ALTER TABLE ox_workflow MODIFY org_id INT(11) NULL");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
