<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190329093848 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
		$this->addSql("ALTER TABLE `ox_email_domain` DROP COLUMN uuid;");
		$this->addSql("DROP TRIGGER IF EXISTS domain_uuid_before_insert;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
