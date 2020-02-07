<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416114523 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM `ox_user_org`");
        $this->addSql("INSERT INTO `ox_user_org` (`user_id`,`org_id`,`default`) VALUES (1,1,1);");
		$this->addSql("INSERT INTO `ox_user_org` (`user_id`,`org_id`,`default`) VALUES (2,1,1);");
	
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("INSERT INTO `ox_user_org` (`user_id`,`org_id`) VALUES (1,1);");
		$this->addSql("INSERT INTO `ox_user_org` (`user_id`,`org_id`) VALUES (2,1);");
    }
}
