<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130110414 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_user_refresh_token` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `salt` varchar(100) NOT NULL,
          `expiry_date` datetime NOT NULL,
          `date_created` datetime NOT NULL,
          `date_modified` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `user_id_UNIQUE` (`user_id`),
          UNIQUE KEY `salt_token_UNIQUE` (`salt`)
        ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
        ");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
