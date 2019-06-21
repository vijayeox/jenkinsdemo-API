<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190620060050 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `ox_role` ADD COLUMN `is_system_role` TINYINT(1) AFTER `org_id`");
        $this->addSql("UPDATE `ox_role` SET is_system_role = 1 WHERE id in (1,2,3,4,5,6)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_role` DROP COLUMN `is_system_role`");
    }
}
