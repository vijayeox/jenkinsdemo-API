<?php declare (strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190122065123 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `ox_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL COMMENT '	',
  `phone_1` varchar(45) NOT NULL COMMENT '	',
  `phone_2` varchar(45) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `address_1` varchar(500) NOT NULL,
  `address_2` varchar(500) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `created_id` int(11) NOT NULL,
  `modified_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_contact`;");

    }
}