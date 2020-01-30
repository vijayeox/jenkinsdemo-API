<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412070103 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("DELETE FROM `ox_organization` WHERE id = 1");
        $this->addSql("INSERT INTO `ox_organization` (`id`,`name`,`address`,`city`,`state`,`zip`,`logo`,`labelfile`,`languagefile`,`status`) VALUES (1,'Cleveland Black','23811 Chagrin Blvd, Ste 244','Beachwood','OH',44122,'cavslogo.png','en','en','Active');");

       $this->addSql("INSERT INTO `ox_organization` (`id`,`name`,`address`,`city`,`state`,`zip`,`logo`,`labelfile`,`languagefile`,`status`) VALUES (2,'Golden State Warriors','California','Oakland','OH',44122,'gswlogo.png','en','en','Active');");

		$this->addSql("INSERT INTO `ox_user_org` (`user_id`,`org_id`) VALUES (1,1);");
		$this->addSql("INSERT INTO `ox_user_org` (`user_id`,`org_id`) VALUES (2,1);");

	}

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM ox_organization WHERE status = 'Active'");
    	$this->addSql("DELETE FROM ox_user_org WHERE org_id = 1");

    }
}
