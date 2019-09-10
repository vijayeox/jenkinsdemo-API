<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190910091209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("ALTER TABLE oxzion_api.ox_payment ADD created_date DATETIME NULL;");
        $this->addSql("ALTER TABLE oxzion_api.ox_payment ADD modified_date DATETIME NULL;");
        $this->addSql("ALTER TABLE oxzion_api.ox_payment ADD created_id BIGINT NULL;");
        $this->addSql("ALTER TABLE oxzion_api.ox_payment ADD modified_id varchar(100) NULL;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
