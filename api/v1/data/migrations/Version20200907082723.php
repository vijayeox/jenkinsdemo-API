<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200907082723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Removing the Audit log table for file attributes table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_insert`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_update`");
        $this->addSql("DROP TRIGGER IF EXISTS `ox_file_attribute_delete`");
        $this->addSql("DROP TABLE ox_file_attributes_audit_log;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
