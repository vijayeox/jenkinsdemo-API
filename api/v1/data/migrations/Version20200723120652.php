<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200723120652 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE ox_user SET username='admintest' WHERE id=1");
        $this->addSql("UPDATE ox_user SET username='managertest' WHERE id=2");
        $this->addSql("UPDATE ox_user SET username='employeetest' WHERE id=3");
        $this->addSql("UPDATE ox_user SET username='orgadmin' WHERE id=4");
        $this->addSql("UPDATE ox_user SET username='org2admin' WHERE id=5");
        $this->addSql("UPDATE ox_user SET firstname='Admin' WHERE id=1");
        $this->addSql("UPDATE ox_user SET firstname='Manager' WHERE id=2");
        $this->addSql("UPDATE ox_user SET firstname='Employee' WHERE id=3");
        $this->addSql("UPDATE ox_user SET firstname='Cleveland' WHERE id=4");
        $this->addSql("UPDATE ox_user SET firstname='Golden' WHERE id=5");
        $this->addSql("UPDATE ox_user SET lastname='Test' WHERE id=1");
        $this->addSql("UPDATE ox_user SET lastname='Test' WHERE id=2");
        $this->addSql("UPDATE ox_user SET lastname='Test' WHERE id=3");
        $this->addSql("UPDATE ox_user SET lastname='Admin' WHERE id=4");
        $this->addSql("UPDATE ox_user SET lastname='Admin' WHERE id=5");
        $this->addSql("UPDATE ox_user SET name='Admin Test' WHERE id=1");
        $this->addSql("UPDATE ox_user SET name='Manager Test' WHERE id=2");
        $this->addSql("UPDATE ox_user SET name='Employee Test' WHERE id=3");
        $this->addSql("UPDATE ox_user SET name='Cleveland Test' WHERE id=4");
        $this->addSql("UPDATE ox_user SET name='Golden Test' WHERE id=5");
        $this->addSql("UPDATE ox_user SET email='admin1@eoxvantage.in' WHERE id=1");
        $this->addSql("UPDATE ox_user SET email='admin2@eoxvantage.in' WHERE id=2");
        $this->addSql("UPDATE ox_user SET email='admin3@eoxvantage.in' WHERE id=3");
        $this->addSql("UPDATE ox_user SET email='admin4@eoxvantage.in' WHERE id=4");
        $this->addSql("UPDATE ox_user SET email='admin5@eoxvantage.in' WHERE id=5");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
