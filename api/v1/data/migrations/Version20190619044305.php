<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190619044305 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	 $this->addSql("DELETE FROM `ox_privilege` WHERE name = 'MANAGE_ORGANIZATION' AND org_id = NULL");
        $this->addSql("ALTER TABLE `ox_privilege` DROP COLUMN `org_id`");
        $this->addSql("UPDATE `ox_role` SET name = UPPER(name)");
        $this->addSql("DELETE FROM `ox_role` where id in(5,6)");
        $this->addSql("INSERT INTO `ox_role` (`id`,`name`,`description`,`org_id`) VALUES(5,'MANAGER','Must have read and write control',1)");
        $this->addSql("INSERT INTO `ox_role` (`id`,`name`,`description`,`org_id`) VALUES(6,'EMPLOYEE','Must have read control',1)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("ALTER TABLE `ox_privilege` ADD COLUMN `org_id` INT(11) AFTER `permission_allowed`");
		$this->addSql("INSERT INTO `ox_privilege` (`name`,`permission_allowed`,`org_id`,`app_id`) VALUES ('MANAGE_ORGANIZATION',15,NULL,1)");
		$this->addSql("DELETE FROM `ox_role` where id in(5,6)");
    }
}
