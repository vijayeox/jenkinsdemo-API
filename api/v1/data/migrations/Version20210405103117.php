<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405103117 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS `oxzion_api`.`ox_user_announcement_mapper` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NOT NULL,
            `announcement_id` INT(11) NOT NULL,
            `view` INT(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`),
            INDEX `fk_ox_user_announcement_mapper_ox_user1_idx` (`user_id` ASC),
            INDEX `fk_ox_user_announcement_mapper_ox_announcement1_idx` (`announcement_id` ASC),
            CONSTRAINT `user_id`
              FOREIGN KEY (`user_id`)
              REFERENCES `oxzion_api`.`ox_user` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION,
            CONSTRAINT `announcement_id`
              FOREIGN KEY (`announcement_id`)
              REFERENCES `oxzion_api`.`ox_announcement` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION)
          ENGINE = InnoDB
          AUTO_INCREMENT = 10
          DEFAULT CHARACTER SET = latin1;
          ");

        

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE ox_user_announcement_mapper");

    }
}
