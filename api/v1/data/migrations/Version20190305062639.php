<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190305062639 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `ox_user_refresh_token` CHANGE `date_created` `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;');
        $this->addSql("ALTER TABLE `ox_user_refresh_token` CHANGE `date_modified` `date_modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
