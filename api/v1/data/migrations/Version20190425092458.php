<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190425092458 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_privilege` SET `app_id` = '946fd092-b4f7-4737-b3f5-14086541492e' WHERE `app_id` = '5cc16490f190d'");
    	$this->addSql("UPDATE `ox_role_privilege` SET `app_id` = '946fd092-b4f7-4737-b3f5-14086541492e' WHERE `app_id` = '5cc16490f190d'");
     
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("UPDATE `ox_privilege` SET `app_id` = '5cc16490f190d' WHERE `app_id` = '946fd092-b4f7-4737-b3f5-14086541492e'");
    	$this->addSql("UPDATE `ox_role_privilege` SET `app_id` = '5cc16490f190d' WHERE `app_id` = '946fd092-b4f7-4737-b3f5-14086541492e'");
    }
}
