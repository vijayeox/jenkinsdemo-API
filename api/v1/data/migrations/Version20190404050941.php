<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404050941 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
       
        $this->addSql("ALTER TABLE `ox_app` ADD UNIQUE INDEX ind_uuid (`uuid`)");
        $this->addSql("ALTER TABLE `ox_app_registry` ADD INDEX ind_orgid (`org_id`)");
        $this->addSql("ALTER TABLE `ox_app_registry` ADD INDEX ind_appid (`app_id`)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
