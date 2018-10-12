<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180930055109 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
    	$this->addSql("CREATE TABLE `ox_attachment` (
			`id` int(32) NOT NULL,
			`uuid` varchar(250) NOT NULL,
			`file_name` varchar(250) NOT NULL,
			`extension` varchar(32) NOT NULL,
			`type` varchar(100) NOT NULL,
			`path` varchar(300) ,
			`created_id` int(32) NOT NULL,
			`org_id` int(32) NOT NULL,
			`created_date` DATETIME DEFAULT CURRENT_TIMESTAMP
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_ATTACHMENT',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_ATTACHMENT',3);");
    }

    public function down(Schema $schema) : void
    {
		$this->addSql("DROP TABLE ox_attachment");

    }
}
