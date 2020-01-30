<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190925151254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("DROP TRIGGER IF EXISTS before_insert_oxfield");
        $this->addSql("DROP TRIGGER IF EXISTS before_insert_oxform");
        $this->addSql("DROP TRIGGER IF EXISTS before_insert_oxfile");
        $this->addSql("CREATE TRIGGER  before_insert_oxfield BEFORE INSERT ON ox_field 
            FOR EACH ROW BEGIN 
                IF (new.uuid is NULL OR new.uuid = '' ) THEN
                    SET new.uuid = uuid();
                END IF;
            END;");
        $this->addSql("CREATE TRIGGER before_insert_oxform BEFORE INSERT ON ox_form 
            FOR EACH ROW BEGIN 
                IF (new.uuid is NULL OR new.uuid = '' ) THEN
                    SET new.uuid = uuid();
                END IF;
            END;");
        $this->addSql("CREATE TRIGGER before_insert_oxfile BEFORE INSERT ON ox_file 
            FOR EACH ROW BEGIN 
                IF (new.uuid is NULL OR new.uuid = '' ) THEN
                    SET new.uuid = uuid();
                END IF;
            END;");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
