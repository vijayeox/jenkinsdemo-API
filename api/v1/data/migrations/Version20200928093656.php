<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200928093656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("ALTER TABLE ox_organization ADD COLUMN parent_id INT(32)");
        $this->addSql("ALTER TABLE ox_organization ADD CONSTRAINT FOREIGN KEY (`parent_id`) REFERENCES ox_organization(`id`)");
        $this->addSql("CREATE TABLE `ox_org_heirarchy` (
            `main_org_id` INT(32) NOT NULL,
            `parent_id` INT(32) NOT NULL,
            `child_id` int(32) NOT NULL,
            FOREIGN KEY (`main_org_id`) REFERENCES ox_organization(`id`),
            FOREIGN KEY (`parent_id`) REFERENCES ox_organization(`id`),
            FOREIGN KEY (`child_id`) REFERENCES ox_organization(`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
