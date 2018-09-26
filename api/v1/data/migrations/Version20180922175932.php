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
		$this->addSql("CREATE TABLE `ox_priveleges` (
			`id` int(32) NOT NULL,
			`name` varchar(250) NOT NULL,
			`permissions_allowed` varchar(32) NOT NULL COMMENT '1=>READ|2=>CREATE|4=>Write|8=>Delete as Sum Ex:15=C+W+R+D',
			`org_id` int(32) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("CREATE TABLE IF NOT EXISTS `ox_roles` (
			`id` int(32) NOT NULL,
			`name` varchar(100) NOT NULL,
			`description` text,
			`org_id` int(32) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("CREATE TABLE IF NOT EXISTS `ox_role_privelege` (
			`id` int(32) NOT NULL,
			`role_id` int(32) NOT NULL,
			`privelege_name` varchar(100) NOT NULL,
			`permissions` int(32) NOT NULL,
			`org_id` int(32) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("CREATE TABLE IF NOT EXISTS `ox_role_user` (
			`user_id` int(32) NOT NULL,
			`role_id` int(32) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

		$this->addSql("ALTER TABLE `ox_priveleges`
			ADD PRIMARY KEY (`id`);");

		$this->addSql("ALTER TABLE `ox_roles`
			ADD PRIMARY KEY (`id`);");

		$this->addSql("ALTER TABLE `ox_role_privelege`
			ADD PRIMARY KEY (`id`);");

		$this->addSql("ALTER TABLE `ox_priveleges`
			MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;");

		$this->addSql("ALTER TABLE `ox_roles`
			MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;");

		$this->addSql("ALTER TABLE `ox_role_privelege`
			MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;");

		$this->addSql("ALTER TABLE `ox_role_privelege` ADD UNIQUE `role_privelege` (`role_id`, `privelege_name`,`org_id`);");

        $this->addSql("INSERT INTO ox_roles (`name`) SELECT distinct Upper(`role`) from avatars");

        $this->addSql("INSERT INTO ox_role_user (`user_id`,`role_id`) SELECT distinct `avatars`.`id` as `user_id`,`ox_roles`.`role_id` from avatars inner join (select `id` as `role_id`, `name` from ox_roles) ox_roles on `ox_roles`.`name` = Upper(`avatars`.`role`)");
        $this->addSql("INSERT INTO ox_roles (id, name, description) values (1, 'EMPLOYEE', NULL), (2, 'EMPLOYEE2', NULL);");

        $this->addSql("INSERT INTO ox_role_user (user_id, role_id) values (1, 1), (1, 2), (2, 2);");
	}

	public function down(Schema $schema) : void
	{
		$this->addSql("DROP TABLE ox_priveleges");
		$this->addSql("DROP TABLE ox_roles");
		$this->addSql("DROP TABLE ox_role_privelege");
		$this->addSql("DROP TABLE ox_role_user");

	}
}
