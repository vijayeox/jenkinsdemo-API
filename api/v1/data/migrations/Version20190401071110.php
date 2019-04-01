<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190401071110 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE oxzionapi.ox_user set preferences=\'{"soundnotification":"true","emailalerts":"false","timezone":"Asia/Calcutta","dateformat":"dd/mm/yyyy"}\' where orgid = 1;');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE oxzionapi.ox_user set preferences=\'\' where orgid = 1;');

    }
}
