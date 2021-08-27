<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202070934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Analytics related table modifications.';
    }

    public function up(Schema $schema) : void
    {
         $this->addSql("INSERT INTO ox_widget_query (ox_widget_id, ox_query_id, configuration) VALUES (5, 2, '')");
         $this->addSql("UPDATE ox_widget_query SET configuration='{\"filter\":null, \"grouping\":null, \"sort\":null}'");
         $this->addSql("ALTER TABLE ox_widget ADD COLUMN expression VARCHAR(1024)");
         $this->addSql("UPDATE ox_widget SET expression='Q1+Q2' WHERE id=1");
         $this->addSql("UPDATE ox_widget SET expression='Q1/Q2*100' WHERE id=4");
    }

    public function down(Schema $schema) : void
    {
        //There are complex table changes and data insertions in "up" migration. It is not possible to cleanly revert the changes. 
        //Therefore "down" migration has not been provided.
    }
}
