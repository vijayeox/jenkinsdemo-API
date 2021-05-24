<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519124931 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql("CREATE TABLE IF NOT EXISTS `ox_business_relationship` (
            `seller_account_business_role_id` int(32) NOT NULL,
            `buyer_account_business_role_id` int(32) NOT NULL,
            FOREIGN KEY (`seller_account_business_role_id`) REFERENCES ox_account_business_role(`id`),
            FOREIGN KEY (`buyer_account_business_role_id`) REFERENCES ox_account_business_role(`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->addSql("ALTER TABLE `ox_file_participant` ADD UNIQUE `accountFileBusinessRole` (`account_id`, `business_role_id`,`file_id`);");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_business_relationship`");
        $this->addSql("ALTER TABLE `ox_file_participant` DROP INDEX `accountFileBusinessRole`");

    }
}
