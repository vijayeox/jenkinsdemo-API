<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190613065240 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql('UPDATE ox_user set username="bharatgtest" where username="bharatg";');
		$this->addSql('UPDATE ox_user set username="karantest" where username="karan";');
		$this->addSql('UPDATE ox_user set username="rakshithtest" where username="rakshith";');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
		$this->addSql('UPDATE ox_user set username="bharatg" where username="bharatgtest";');
		$this->addSql('UPDATE ox_user set username="karan" where username="karantest";');
		$this->addSql('UPDATE ox_user set username="rakshith" where username="rakshithtest";');

    }
}
