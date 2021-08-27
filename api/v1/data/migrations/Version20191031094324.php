<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191031094324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Error Log TABLE';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE IF NOT EXISTS ox_error_log (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `error_type` TEXT NOT NULL,
            `error_trace` MEDIUMTEXT NULL,
            `payload` MEDIUMTEXT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `ox_error_log`");
    }
}
