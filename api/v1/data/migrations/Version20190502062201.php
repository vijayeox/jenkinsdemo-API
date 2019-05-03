<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190502062201 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_user_role` SET  `role_id` = 4 WHERE `role_id` = 1");
    	$this->addSql("UPDATE `ox_user_role` SET  `role_id` = 5 WHERE `role_id` = 2");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_user_role` SET  `role_id` = 1 WHERE `role_id` = 4");
    	$this->addSql("UPDATE `ox_user_role` SET  `role_id` = 2 WHERE `role_id` = 5");
    }
}
