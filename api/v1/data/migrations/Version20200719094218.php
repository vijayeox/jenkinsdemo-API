<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200719094218 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_organization ADD COLUMN type ENUM('BUSINESS','INDIVIDUAL') NOT NULL DEFAULT 'BUSINESS'");
        $this->addSql("ALTER TABLE ox_organization ADD COLUMN name VARCHAR(100) NULL");
        $this->addSql("UPDATE ox_organization INNER JOIN ox_organization_profile on ox_organization_profile.id = ox_organization.org_profile_id 
                        SET ox_organization.name = ox_organization_profile.name");    
        $this->addSql("ALTER TABLE ox_organization MODIFY COLUMN name VARCHAR(100) NOT NULL");
        $this->addSql("ALTER TABLE ox_organization_profile DROP COLUMN name");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
