<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190416051403 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("DELETE FROM `ox_role` WHERE `id` in (4,5,6)");
    	$this->addSql("ALTER TABLE `ox_role` ADD CONSTRAINT uniq_role UNIQUE (`name`,`org_id`)");
		$this->addSql("UPDATE `ox_role` SET `description` = 'Must have read,write,create and delete control',`org_id`= 1 WHERE name = 'ADMIN'");
		$this->addSql("UPDATE `ox_role` SET `description` = 'Must have read and write control',`org_id`= 1 WHERE name = 'MANAGER'");
		$this->addSql("UPDATE `ox_role` SET `description` = 'Must have read control',`org_id`= 1 WHERE name = 'EMPLOYEE'");
        $this->addSql("UPDATE `ox_field` SET `org_id` = 1,`name` = 'field1',`data_type` = 'text',options = NULL WHERE id = 1");	
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_role` SET `description` = NULL WHERE name in ('ADMIN','MANAGER','EMPLOYEE')");
		
    }
}
