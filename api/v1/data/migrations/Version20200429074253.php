<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200429074253 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Login screen table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_announcement ADD COLUMN type ENUM('HOMESCREEN','ANNOUNCEMENT') NOT NULL");
        $this->addSql("ALTER TABLE ox_organization ADD COLUMN subdomain varchar(64)");
        $this->addSql("ALTER TABLE ox_announcement MODIFY org_id INT(11) NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_announcement` DROP COLUMN `type`");
        $this->addSql("ALTER TABLE `ox_organization` DROP COLUMN `subdomain`");
        $this->addSql("ALTER TABLE ox_announcement MODIFY org_id INT(11) NOT NULL");
    }
}
