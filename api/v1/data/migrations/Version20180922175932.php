<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180922175932 extends AbstractMigration
{
	public function up(Schema $schema) : void
	{
		$this->addSql("CREATE TABLE `ox_privilege` (
			`id` int(32) NOT NULL,
			`name` varchar(250) NOT NULL,
			`permission_allowed` varchar(32) NOT NULL COMMENT '1=>READ|2=>CREATE|4=>Write|8=>Delete as Sum Ex:15=C+W+R+D',
			`org_id` int(32)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("CREATE TABLE IF NOT EXISTS `ox_role` (
			`id` int(32) NOT NULL,
			`name` varchar(100) NOT NULL,
			`description` text,
			`org_id` int(32) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("CREATE TABLE IF NOT EXISTS `ox_role_privilege` (
			`id` int(32) NOT NULL,
			`role_id` int(32) NOT NULL,
			`privilege_name` varchar(100) NOT NULL,
			`permission` int(32) NOT NULL,
			`org_id` int(32) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("CREATE TABLE IF NOT EXISTS `ox_user_role` (
			`user_id` int(32) NOT NULL,
			`role_id` int(32) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("ALTER TABLE `ox_privilege`
			ADD PRIMARY KEY (`id`);");

		$this->addSql("ALTER TABLE `ox_role`
			ADD PRIMARY KEY (`id`);");

		$this->addSql("ALTER TABLE `ox_role_privilege`
			ADD PRIMARY KEY (`id`);");

		$this->addSql("ALTER TABLE `ox_privilege`
			MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;");

		$this->addSql("ALTER TABLE `ox_role`
			MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;");

		$this->addSql("ALTER TABLE `ox_role_privilege`
			MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;");

		$this->addSql("ALTER TABLE `ox_role_privilege` ADD UNIQUE `role_privilege` (`role_id`, `privilege_name`,`org_id`);");

        $this->addSql("INSERT INTO ox_role (`name`) SELECT distinct Upper(`role`) from avatars");

        $this->addSql("INSERT INTO ox_user_role (`user_id`,`role_id`) SELECT distinct `avatars`.`id` as `user_id`,`ox_role`.`role_id` from avatars inner join (select `id` as `role_id`, `name` from ox_role) ox_role on `ox_role`.`name` = Upper(`avatars`.`role`)");

        $this->addSql("INSERT INTO ox_user_role (user_id, role_id) values (1, 1), (1, 2), (2, 2);");

        $this->addSql("INSERT INTO ox_privilege (id, name,permission_allowed) values (1, 'MANAGE_ANNOUNCEMENT',3);");
        $this->addSql("INSERT INTO ox_privilege (id, name,permission_allowed) values (2, 'MANAGE_ALERT',3);");
        $this->addSql("INSERT INTO ox_privilege (id, name,permission_allowed) values (3, 'MANAGE_SCREEN',3);");
        $this->addSql("INSERT INTO ox_privilege (id, name,permission_allowed) values (4, 'MANAGE_WIDGET',3);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (1,1, 'MANAGE_ANNOUNCEMENT',3);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (2,2, 'MANAGE_ANNOUNCEMENT',1);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (3,1, 'MANAGE_ALERT',3);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (4,2, 'MANAGE_ALERT',1);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (5,1, 'MANAGE_SCREEN',3);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (6,2, 'MANAGE_SCREEN',1);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (7,1, 'MANAGE_WIDGET',3);");
        $this->addSql("INSERT INTO ox_role_privilege (id, role_id,privilege_name,permission) values (8,2, 'MANAGE_WIDGET',1);");
	}

	public function down(Schema $schema) : void
	{
		$this->addSql("DROP TABLE ox_privilege");
		$this->addSql("DROP TABLE ox_role");
		$this->addSql("DROP TABLE ox_role_privilege");
		$this->addSql("DROP TABLE ox_user_role");

	}
}
