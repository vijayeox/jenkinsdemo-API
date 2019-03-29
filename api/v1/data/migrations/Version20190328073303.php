<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328073303 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // this up() migration is auto-generated, please modify it to your needs
		// $this->addSql("DROP TRIGGER IF EXISTS `before_insert_ox_user`");
		$dbname =$this->connection->getDatabase();
		$host = $this->connection->getHost();
		$username = $this->connection->getUsername();
		$password = $this->connection->getPassword();
		$xyz =exec("/usr/bin/mysql -u ".$username." -p'".$password."' ".$dbname." -h ".$host." < ".__DIR__."/Version20190328073303.sql");
    
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TRIGGER IF EXISTS `before_insert_ox_user`");
    }
}
