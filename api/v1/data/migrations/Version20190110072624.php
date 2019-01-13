<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190110072624 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TRIGGER  before_insert_app BEFORE INSERT ON ox_app FOR EACH ROW SET new.uuid = uuid()");
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
