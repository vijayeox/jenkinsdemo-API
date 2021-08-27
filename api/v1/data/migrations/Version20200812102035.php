<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200812102035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $sql = "INSERT INTO ox_file_document (file_id, field_id, org_id, field_value, sequence,
                            date_created, created_by, date_modified, modified_by)
                          (SELECT fa.file_id, fa.field_id, fa.org_id, fa.field_value, fa.sequence, 
                            fa.date_created, fa.created_by, fa.date_modified, fa.modified_by from ox_file_attribute fa 
                            inner join ox_field f on f.id = fa.field_id
                            where f.type = 'document')";
        $this->addSql($sql);

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
