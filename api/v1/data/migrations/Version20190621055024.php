<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190621055024 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_user` SET orgid = NULL WHERE orgid = 0");
        $this->addSql("ALTER TABLE `ox_user` CHANGE `orgid` `orgid` INT(11) NULL,ADD CONSTRAINT FOREIGN KEY (`orgid`) REFERENCES ox_organization(`id`)");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_user` SET orgid = 0 WHERE orgid = NULL");
    }
}
