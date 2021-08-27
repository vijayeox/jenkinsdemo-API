<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519095710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_file_attribute ADD COLUMN field_value_type ENUM('TEXT','DATE','NUMERIC','BOOLEAN','OTHER') NOT NULL");
        $this->addSql("ALTER TABLE ox_file_attribute ADD COLUMN field_value_text VARCHAR(1024)");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa INNER JOIN ox_field of2 on of2.id = ofa.field_id Where (of2.data_type = 'text' or of2.data_type = 'document' or of2.data_type = 'file' or of2.data_type = 'json' or of2.data_type = 'list')) AS M on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_text = M.field_value, ox_file_attribute.field_value_type = 'TEXT'");
        $this->addSql("ALTER TABLE ox_file_attribute ADD COLUMN field_value_numeric FLOAT");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa INNER JOIN ox_field of2 on of2.id = ofa.field_id Where of2.data_type = 'numeric') AS M on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_numeric = M.field_value, ox_file_attribute.field_value_type = 'NUMERIC'");
        $this->addSql("ALTER TABLE ox_file_attribute ADD COLUMN field_value_boolean TINYINT(1)");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa 
                        INNER JOIN ox_field of2 on of2.id = ofa.field_id Where of2.data_type = 'boolean') AS M 
                        on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_boolean = case when M.field_value='true' 
                        then 1 when M.field_value='false' then 0 else M.field_value End,
                        ox_file_attribute.field_value_type = 'BOOLEAN'");
        $this->addSql("ALTER TABLE ox_file_attribute ADD COLUMN field_value_date DATETIME");
        $this->addSql("UPDATE ox_file_attribute inner join (Select ofa.id as id,ofa.field_value from ox_file_attribute ofa INNER JOIN ox_field of2 on of2.id = ofa.field_id Where of2.data_type = 'date' OR of2.data_type = 'datetime') AS M on M.id = ox_file_attribute.id SET ox_file_attribute.field_value_date = M.field_value, ox_file_attribute.field_value_type = 'DATE'");
        $this->addSql("ALTER TABLE ox_file_attribute ADD INDEX `numeric_value_index` (`field_value_numeric`)");
        $this->addSql("ALTER TABLE ox_file_attribute ADD INDEX `text_value_index` (`field_value_text`)");
        $this->addSql("ALTER TABLE ox_file_attribute ADD INDEX `boolean_value_index` (`field_value_boolean`)");
        $this->addSql("ALTER TABLE ox_file_attribute ADD INDEX `date_value_index` (`field_value_date`)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE ox_file_attribute DROP COLUMN field_value_numeric,DROP COLUMN field_value_text,DROP COLUMN field_value_boolean,DROP COLUMN field_value_date,DROP COLUMN field_value_type");
        $this->addSql("ALTER TABLE ox_file_attribute DROP INDEX numeric_value_index,DROP INDEX text_value_index,DROP INDEX boolean_value_index,DROP INDEX date_value_index");
        $this->addSql("ALTER TABLE ox_file_attribute DROP COLUMN field_value_numeric,DROP COLUMN field_value_text,DROP COLUMN field_value_boolean,DROP COLUMN field_value_date,DROP COLUMN field_value_type");
    }
}
