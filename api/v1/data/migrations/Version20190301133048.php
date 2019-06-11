<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190301133048 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS`ox_email_domain` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid` varchar(128) DEFAULT NULL,
            `name` varchar(1000) NOT NULL,
            `imap_server` varchar(1000) NOT NULL,
            `imap_port` varchar(1000) NOT NULL DEFAULT '143',
            `imap_secure` varchar(1000) DEFAULT NULL,
            `imap_short_login` varchar(1000) DEFAULT NULL,
            `smtp_server` varchar(1000) NOT NULL,
            `smtp_port` varchar(1000) NOT NULL DEFAULT '25',
            `smtp_secure` varchar(1000) DEFAULT NULL,
            `smtp_short_login` varchar(1000) DEFAULT NULL,
            `smtp_auth` varchar(1000) DEFAULT NULL,
            `smtp_use_php_mail` varchar(10000) DEFAULT NULL,
            `created_by` int(11) NOT NULL,
            `modified_id` int(11) DEFAULT NULL,
            `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `date_modified` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;");
        $this->addSql("INSERT INTO `ox_role_privilege` (`role_id`, `privilege_name`, `permission`) VALUES ('1', 'MANAGE_DOMAIN', '15')");
        $this->addSql("CREATE TRIGGER  domain_uuid_before_insert BEFORE INSERT ON ox_email_domain FOR EACH ROW SET new.uuid = uuid();");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql("DROP TABLE `ox_email_domain`");
        $this->addSql("DELETE FROM `ox_role_privilege` WHERE `privilege_name` LIKE 'MANAGE_DOMAIN'");
    	$this->addSql("DROP TRIGGER IF EXISTS `domain_uuid_before_insert`");
    }
}
