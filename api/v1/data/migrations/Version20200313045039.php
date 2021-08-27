<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313045039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_job ADD CONSTRAINT FK_job_appid FOREIGN KEY (app_id) REFERENCES ox_app(id)");
        $this->addSql("ALTER TABLE ox_job ADD CONSTRAINT FK_job_orgid FOREIGN KEY (org_id) REFERENCES ox_organization(id)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_job DROP FOREIGN KEY FK_job_appid");
        $this->addSql("ALTER TABLE ox_job DROP FOREIGN KEY FK_job_orgid");

    }
}
