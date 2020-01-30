<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701070807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding org_id to all tables along with unique datasource constraint';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE datasource ADD org_id INT(11)");
        $this->addSql("ALTER TABLE query ADD org_id INT(11)");
        $this->addSql("ALTER TABLE visualization ADD org_id INT(11)");
        $this->addSql("ALTER TABLE widget ADD org_id INT(11)");
        $this->addSql("ALTER TABLE dashboard ADD org_id INT(11)");
        $this->addSql("ALTER TABLE datasource ADD CONSTRAINT datasource_unique UNIQUE (name, org_id);");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE datasource DROP INDEX datasource_unique");
        $this->addSql("ALTER TABLE dashboard DROP COLUMN org_id");
        $this->addSql("ALTER TABLE widget DROP COLUMN org_id");
        $this->addSql("ALTER TABLE visualization DROP COLUMN org_id");
        $this->addSql("ALTER TABLE query DROP COLUMN org_id");
        $this->addSql("ALTER TABLE datasource DROP COLUMN org_id");
    }
}
