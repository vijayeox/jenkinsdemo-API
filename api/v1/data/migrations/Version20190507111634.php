<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190507111634 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE `ox_user` SET `email` = 'test1@va.com' WHERE `id` = 2");
    	$this->addSql("ALTER TABLE `ox_user` ADD UNIQUE (email)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_user` SET `email` = 'test@va.com' WHERE `id` = 2");
    }
}
