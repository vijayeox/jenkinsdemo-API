<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181108042050 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("RENAME TABLE `user_org` TO `ox_user_org`;");
    }

    public function down(Schema $schema) : void
    {
    	$this->addSql("RENAME TABLE `ox_user_org` TO `user_org`;");
        // this down() migration is auto-generated, please modify it to your needs

    }
}
