<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925083255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Change default Passwords for Default Users';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_user SET password='59f6de0992965e8f489e21470fdd544d' WHERE id=1");
        $this->addSql("UPDATE ox_user SET password='59f6de0992965e8f489e21470fdd544d' WHERE id=2");
        $this->addSql("UPDATE ox_user SET password='59f6de0992965e8f489e21470fdd544d' WHERE id=3");
        $this->addSql("UPDATE ox_user SET password='59f6de0992965e8f489e21470fdd544d' WHERE id=4");
        $this->addSql("UPDATE ox_user SET password='59f6de0992965e8f489e21470fdd544d' WHERE id=5");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_user SET password='1619d7adc23f4f633f11014d2f22b7d8' WHERE id=1");
        $this->addSql("UPDATE ox_user SET password='1619d7adc23f4f633f11014d2f22b7d8' WHERE id=2");
        $this->addSql("UPDATE ox_user SET password='1619d7adc23f4f633f11014d2f22b7d8' WHERE id=3");
        $this->addSql("UPDATE ox_user SET password='1619d7adc23f4f633f11014d2f22b7d8' WHERE id=4");
        $this->addSql("UPDATE ox_user SET password='1619d7adc23f4f633f11014d2f22b7d8' WHERE id=5");
    }
}
