<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181011165222 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `user_org` ( `user_id` int(32) NOT NULL, `org_id` int(32) NOT NULL, `default` int(5) NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->addSql("INSERT INTO user_org (`user_id`, `org_id`) VALUES (1,1),(2,1),(3,1)");
        $this->addSql("UPDATE `user_org` SET `default` = '1';");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE user_org");

    }
}
