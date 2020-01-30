<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190915012846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Alter dashboard related tables and add sample data for testing.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_datasource MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_datasource MODIFY type VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_datasource ADD ispublic BOOLEAN NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE ox_datasource ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE ox_visualization ADD COLUMN configuration TEXT NOT NULL");
        $this->addSql("ALTER TABLE ox_visualization ADD COLUMN renderer VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_visualization ADD COLUMN type VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_visualization MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_visualization ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE ox_query MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_query ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE ox_widget MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_widget MODIFY configuration TEXT NOT NULL");
        $this->addSql("ALTER TABLE ox_widget ADD version INTEGER NOT NULL DEFAULT 0");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY uuid CHAR(36) NOT NULL UNIQUE");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY dashboard_type VARCHAR(16) NOT NULL");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY content TEXT NOT NULL");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY ispublic boolean NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE ox_dashboard MODIFY isdeleted boolean NOT NULL DEFAULT false");
        $this->addSql("ALTER TABLE ox_dashboard ADD version INTEGER NOT NULL DEFAULT 0");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DELETE FROM ox_dashboard WHERE uuid='c6318742-b9f9-4a18-abce-7a7fbbac8c8b'");
        $this->addSql("ALTER TABLE ox_dashboard DROP version");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='08bd8fc1-2336-423d-842a-7217a5482dc9'");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c'");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='bacb4ec3-5f29-49d7-ac41-978a99d014d3'");
        $this->addSql("DELETE FROM ox_widget WHERE uuid='2aab5e6a-5fd4-44a8-bb50-57d32ca226b0'");
        $this->addSql("ALTER TABLE ox_widget DROP version");
        $this->addSql("DELETE FROM ox_query WHERE uuid='de5c309d-6bd6-494f-8c34-b85ac109a301'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='69f7732a-998a-41bb-ab89-aa7c434cb327'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='45933c62-6933-43da-bbb2-59e6f331e8db'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='3c0c8e99-9ec8-4eac-8df5-9d6ac09628e7'");
        $this->addSql("DELETE FROM ox_query WHERE uuid='bf0a8a59-3a30-4021-aa79-726929469b07'");
        $this->addSql("ALTER TABLE ox_query DROP version");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='0439fc08-f855-434d-8f24-c38424056963'");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='e0658f1e-f84c-4d9b-b209-d2378730f776'");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='d4abdb81-a12a-4fc2-86ee-7145815beb61'");
        $this->addSql("DELETE FROM ox_visualization WHERE uuid='153f4f96-9b6c-47db-95b2-104af23e7522'");
        $this->addSql("ALTER TABLE ox_visualization DROP COLUMN type");
        $this->addSql("ALTER TABLE ox_visualization DROP COLUMN renderer");
        $this->addSql("ALTER TABLE ox_visualization DROP COLUMN configuration");
        $this->addSql("ALTER TABLE ox_visualization DROP version");
        $this->addSql("DELETE FROM ox_datasource WHERE uuid='d08d06ce-0cae-47e7-9c4f-a6716128a303'");
        $this->addSql("ALTER TABLE ox_datasource DROP ispublic");
        $this->addSql("ALTER TABLE ox_datasource DROP version");
    }
}

