<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200817100830 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_org_offering` ( 
                `org_business_role_id` Int(32) NOT NULL,
                `entity_id` Int( 32 ) NOT NULL,
                FOREIGN KEY (`org_business_role_id`) REFERENCES ox_org_business_role(`id`),
                FOREIGN KEY (`entity_id`) REFERENCES ox_app_entity(`id`))
                ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_entity_participant_role` ( 
                `business_role_id` Int(32) NOT NULL,
                `entity_id` Int( 32 ) NOT NULL,
                FOREIGN KEY (`business_role_id`) REFERENCES ox_business_role(`id`),
                FOREIGN KEY (`entity_id`) REFERENCES ox_app_entity(`id`))
                ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->addSql("CREATE TABLE IF NOT EXISTS `ox_file_participant` ( 
                `id` Int( 32 ) AUTO_INCREMENT NOT NULL,
                `file_id` Int( 64 ) NOT NULL,
                `org_id` Int( 32 ) NOT NULL,
                `business_role_id` INT(32) NOT NULL,
                PRIMARY KEY ( `id` ),
                FOREIGN KEY (`business_role_id`) REFERENCES ox_business_role(`id`),
                FOREIGN KEY (`org_id`) REFERENCES ox_organization(`id`),
                FOREIGN KEY (`file_id`) REFERENCES ox_file(`id`))
                ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");
        
    }

    public function down(Schema $schema) : void
    {
		$this->addSql("DROP TABLE `ox_org_offering`");
		$this->addSql("DROP TABLE `ox_entity_participant_role`");
        $this->addSql("DROP TABLE `ox_file_participant`");

    }
}
