<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413153905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
            try{

              $this->migrateData();
          }catch(Exception $e){
            print_r("expression\n");
            print_r($e->getTraceAsString());exit;
          }
              $this->addSql("DELETE ofa FROM ox_file_attribute ofa JOIN ox_file `of` ON of.id=ofa.file_id WHERE of.latest=0");
    }

    protected function migrateData(){
            $selectQuery = $this->connection->executeQuery("SELECT id,parent_id,workflow_instance_id,data FROM ox_file WHERE latest=1;");
            $resultQuery = $selectQuery->fetchAll();
            foreach ($resultQuery as $key => $value) {   
                $updateParams = array('fileId' => $value['id'],'data' => $value['data'],'wfId' => $value['workflow_instance_id']); 
                $update = $this->connection->executeUpdate("UPDATE ox_workflow_instance SET file_id =:fileId,file_data = :data WHERE id= :wfId ;",$updateParams);     
                if($value['parent_id']!=null){
                    $this->updateParent($value['parent_id'],$value['id']);
                }
            } 
    }

    protected function updateParent($parentID,$fileID){
        try{
            $select = $this->connection->executeQuery("SELECT data,parent_id,workflow_instance_id FROM ox_file WHERE id = ".$parentID.";");
            $selectResult = $select->fetchAll();
            foreach ($selectResult as $key => $value) {
                $updateParams = array('fileId' => $fileID,'data' => $value['data'],'wfId' => $value['workflow_instance_id']); 
                $update = $this->connection->executeUpdate("UPDATE ox_workflow_instance SET file_id = :fileId,file_data = :data WHERE id= :wfId;",$updateParams);
                 if($value['parent_id']!=null){
                    $this->updateParent($value['parent_id'],$fileID);
                 }
            }
        }catch(Exception $e){
            // print_r($e->getTraceAsString());exit;
            print_r("Version20200413153905 exception");
        }

    } 

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
         // $this->addSql("ALTER TABLE `ox_file` ADD COLUMN `workflow_instance_id` INT(32");
        // $this->addSql("ALTER TABLE ox_file ADD COLUMN `parent_id` INT(32) NULL ");
          // $this->addSql("ALTER TABLE ox_file ADD CONSTRAINT FK_FileParentId FOREIGN KEY (parent_id) REFERENCES ox_file(id);");

    }
}
