<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190918122508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_organization ADD FOREIGN KEY (contactid) REFERENCES ox_user(id)");
        $this->addSql('UPDATE ox_user set preferences=\'{"currency":"USD","timezone":"United States/New York","dateformat":"dd/mm/yyyy"}\' where id in (1,2,3,4,5);');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE ox_user set preferences=\'{"currency":"INR","timezone":"Asia/Calcutta","dateformat":"dd/mm/yyyy"}\' where id in (1,2,3);');
        $this->addSql('UPDATE ox_user set preferences = NULL where id in (4,5);');
    }
}
