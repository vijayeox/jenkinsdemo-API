<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016171009 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_file` ( 
            `id` INT(32) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `uuid` VARCHAR(128) NOT NULL ,
            `name` VARCHAR(250) NOT NULL ,
            `org_id` INT(64) NOT NULL ,
            `form_id` INT(32) NOT NULL ,
            `status` INT(11) NOT NULL ,
            `data` TEXT NOT NULL ,
            `created_by` INT(32) NOT NULL DEFAULT '1',
            `modified_by` INT(32) ,
            `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `date_modified`  DATETIME ) ENGINE = InnoDB;");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_file_attribute` ( 
            `id` INT(32) NOT NULL AUTO_INCREMENT  PRIMARY KEY, 
            `fileid` INT(64) NOT NULL , 
            `fieldid` VARCHAR(250) NOT NULL , 
            `fieldvalue` TEXT  NULL , 
            `org_id` INT NOT NULL ) ENGINE = InnoDB;");
        $this->addSql("INSERT INTO `ox_file` (`id`,`uuid`, `name`, `org_id`, `form_id`,`status`, `created_by`, `modified_by`, `date_created`,`date_modified`) SELECT `id`,UUID() , `name`, `orgid`, `formid`,`status`, `createdid`, `modifiedid`,  `date_created`, `date_modified` from instanceforms");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_form` ( 
            `id` INT NOT NULL AUTO_INCREMENT ,
            `uuid` varchar(128) NOT NULL ,
            `name` VARCHAR(500) NOT NULL ,
            `description` VARCHAR(500) ,
            `org_id` INT NOT NULL ,
            `statuslist` TEXT NULL ,
            `template` TEXT NULL ,
            `created_by` INT(32) NOT NULL DEFAULT '1',
            `modified_by` INT(32) ,
            `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `date_modified`  DATETIME ,
            PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        $this->addSql("INSERT INTO `ox_form` (`id`,`uuid`, `name`, `description`,`statuslist`, `org_id`) SELECT `id`,UUID() , `name`, `description`, `statuslist`,`orgid` from `metaforms`");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_field` ( 
            `id` INT(32) NOT NULL AUTO_INCREMENT ,
            `uuid` VARCHAR(128) NOT NULL ,
            `name` VARCHAR(250) NOT NULL ,
            `text` VARCHAR(500),
            `form_id` VARCHAR(128) NOT NULL ,
            `org_id` INT(32)  NOT NULL ,
            `data_type` VARCHAR(32) NOT NULL ,
            `options` VARCHAR(1000) ,
            `dependson` VARCHAR(50) ,
            `default_value` VARCHAR(250) ,
            `required` INT(1) NOT NULL DEFAULT '0' ,
            `readonly` INT(1) ,
            `expression` VARCHAR(1000) ,
            `validationtext` VarChar( 250 ) ,
            `helpertext` VARCHAR(250) ,
            `sequence` INT(11) NOT NULL ,
            `created_by` INT(32) NOT NULL DEFAULT '1',
            `modified_by` INT(32) ,
            `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `date_modified`  DATETIME, 
            PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        // $this->addSql("INSERT INTO `ox_field` (`id`, `uuid`, `name`, `text`, `formid`, `data_type`, `options`, `dependson`, `required`, `readonly`, `expression`,`validationtext`,  `helpertext`, `sequence`) SELECT `metafields`.`id`,UUID() ,`metafields`.`name`, `metafields`.`text`, `ox_form`.`id`, `metafields`.`type`, `options`, `dependson`, `required`, `readonly`, `expression`,`validationtext` , `helpertext`, `sequence` from `metafields` inner join `ox_form` on `ox_form`.`id` = `metafields`.`formid`");
        $this->addSql("CREATE TABLE  IF NOT EXISTS `ox_metafield` ( 
            `id` Int( 11 ) AUTO_INCREMENT NOT NULL ,
            `name` VarChar( 100 ) NOT NULL ,
            `text` VarChar( 400 ),
            `helpertext` VarChar( 150 ) ,
            `org_id` Int( 32 ) NOT NULL ,
            `data_type` VarChar( 30 ) ,
            `options` VarChar( 10000 ) ,
            `validationtext` VarChar( 250 ) ,
            `expression` VarChar( 1000 ) ,
            `created_by` INT(32) NOT NULL DEFAULT '1',
            `modified_by` INT(32) ,
            `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `date_modified`  DATETIME, 
            PRIMARY KEY ( `id` ) )
            ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");
        // $this->addSql("INSERT INTO ox_metafield (`name`, `text`,`org_id`, `helpertext`, `data_type`,`options`,`validationtext`,`expression`)  SELECT distinct mf.`name`, `text`,`metaforms`.`orgid`, `helpertext`,`mf`.`type`, `options`,validationtext, expression from metafields mf inner join `metaforms` ON `metaforms`.`id`=`mf`.`formid` ");
        // $this->addSql("DROP TABLE fields");
        $this->addSql("ALTER TABLE `ox_file` ADD UNIQUE `fileIndex` (`id`);");
        $this->addSql("ALTER TABLE `ox_field` ADD UNIQUE `field_id` (`id`);");
        $this->addSql("ALTER TABLE `ox_field` ADD INDEX `oxFormIndex` (`id`,`form_id` ,`created_by`, `modified_by`, `org_id`);");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD UNIQUE `fileIdIndex` (`id`);");
        $this->addSql("ALTER TABLE `ox_file_attribute` ADD INDEX `fieldIdFileIdIndex` (`id`, `fileid`);");
        $this->addSql("ALTER TABLE `ox_form` ADD UNIQUE `formId` (`id`);");
        $this->addSql("ALTER TABLE `ox_form` ADD INDEX `oxFormIndex` (`id`, `created_by`, `modified_by`, `org_id`);");
        $this->addSql("CREATE TRIGGER  IF NOT EXISTS before_insert_oxfield BEFORE INSERT ON ox_field FOR EACH ROW SET new.uuid = uuid()");
        $this->addSql("CREATE TRIGGER IF NOT EXISTS before_insert_oxform BEFORE INSERT ON ox_form FOR EACH ROW SET new.uuid = uuid()");
        $this->addSql("CREATE TRIGGER IF NOT EXISTS before_insert_oxfile BEFORE INSERT ON ox_file FOR EACH ROW SET new.uuid = uuid()");
        $this->addSql("INSERT INTO `ox_form` (`id`, `uuid`, `name`, `description`, `org_id`, `statuslist`, `template`, `created_by`, `modified_by`, `date_created`, `date_modified`) VALUES (NULL, '', 'Task', 'Basic Task Form', '1', '[{1:\"In Progress\"},{2:\"Completed\"}]', NULL, '1', NULL, CURRENT_TIMESTAMP, NULL);");
        $this->addSql("INSERT INTO `ox_field` (`id`, `uuid`, `name`, `text`, `form_id`, `data_type`, `options`, `dependson`, `default_value`, `required`, `readonly`, `expression`, `validationtext`, `helpertext`, `sequence`, `created_by`, `modified_by`, `date_created`, `date_modified`) VALUES (NULL, '', 'priority', 'Priority', '1', 'select', '[{1:\"Low\"},{2:\"Medium\"},{3:\"High\"}]', NULL, NULL, '1', NULL, NULL, NULL, NULL, '1', '1', NULL, CURRENT_TIMESTAMP, NULL);");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_FILE',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_FILE',15);");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_FORM',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_FORM',15);");
        $this->addSql("INSERT INTO ox_privilege (name,permission_allowed) values ('MANAGE_FIELD',15);");
        $this->addSql("INSERT INTO ox_role_privilege (role_id,privilege_name,permission) values (1, 'MANAGE_FIELD',15);");
    }
    public function forUsageInFuture(Schema $schema) {
        echo "\n";
        echo "Running Migrations"."\n";
        $fieldoptions = $this->connection->executeQuery("SELECT id,options from ox_field WHERE `options` IS NOT NULL AND `options` <> '' ORDER BY `id` ASC");
        $fieldsArray = $fieldoptions->fetchAll();
        echo "Setting Options Array"."\n";
        foreach ($fieldsArray as $key => $field) {
            $optionsArray = $this->convertOptionsArray($field['options']);
            $this->connection->executeUpdate("UPDATE `ox_field` SET `options` = '".$optionsArray."' WHERE `id` = ".$field['id'].";");
        }
        echo "Finished Cleaning Up Options Array"."\n";
        $forms = $this->connection->executeQuery("SELECT id,statuslist,org_id from ox_form ORDER BY `id` ASC");
        $columns = $this->connection->executeQuery("SELECT COLUMN_NAME FROM `information_schema`.`COLUMNS` WHERE TABLE_NAME = 'instanceforms' and TABLE_SCHEMA = 'oxapi' and COLUMN_NAME NOT IN('id','uuid', 'name', 'orgid', 'form_id', 'createdid', 'modifiedid', 'date_created','date_modified')");
        $columnarray = $columns->fetchAll();
        foreach ($forms->fetchAll() as $key => $form) {
            echo "\n";
            echo "Inserting Form :".$form['id']."\n";
            $result = $this->connection->insert('ox_field', array(
                'name'=> 'name',
                'text'=> 'Name',
                'form_id'=> $form['id'],
                'data_type'=> 'text',
                'required'=> 1,
                'sequence'=> 1,
            ));
            if($form['statuslist']){
                echo "Setting Form  Status Fields :".$form['id']."\n";
                $statusArray = $this->convertOptionsArray($form['statuslist']);
                $this->connection->executeUpdate("UPDATE `ox_form` SET `statuslist` = '".$statusArray."' WHERE `id` = ".$form['id'].";");
            } else {
                $status = $this->connection->executeQuery("SELECT statusvalue,statusname from metastatus WHERE formid=".$form['id']." AND orgid=".$form['org_id']);
                $statusArray = $status->fetchAll();
                if($statusArray){
                    $statusOptions = array();
                    foreach ($statusArray as $key => $value) {
                        $statusOptions[$value['statusvalue']] = $value['statusname'];
                    }
                    $statusJson = json_encode(array('data'=>$statusOptions));
                    $this->connection->executeUpdate("UPDATE `ox_form` SET `statuslist` = '".$statusJson."' WHERE `id` = ".$form['id'].";");
                }
            }
            echo "Setting Column Names for Form :".$form['id']."\n";
            foreach ($columnarray as $k => $column) {
                $field = array();
                $fields = $this->connection->executeQuery("SELECT id,name FROM metafields WHERE formid=".$form['id']." AND columnname='".$column['COLUMN_NAME']."'");
                if($field = $fields->fetchAll()){
                    echo $field[0]['id']."\n";
                    echo "Inserting Column :".$column['COLUMN_NAME']."\n";
                    $insertQuery = $this->connection->executeUpdate("INSERT INTO ox_file_attribute (`fileid`, `fieldid`, `org_id`, `fieldvalue`)
                    SELECT `id`, '".$field[0]['id']."', `orgid`, `".$column['COLUMN_NAME']."`
                    FROM instanceforms WHERE formid=".$form['id'].";");
                    echo $insertQuery." Completed"."\n";
                } else {
                    if(in_array($column['COLUMN_NAME'],array('description','parentinstformid','startdate','nextactiondate','enddate'))){
                        echo "Inserting Column :".$column['COLUMN_NAME']."\n";
                        $fieldId = null;
                        if($column['COLUMN_NAME']=='startdate'){
                            $result = $this->connection->insert('ox_field', array(
                                'name'=> 'startdate',
                                'text'=> 'Start Date',
                                'formid'=> $form['id'],
                                'data_type'=> 'datetime',
                                'required'=> 1,
                                'sequence'=> 1,
                            ));
                        }
                        if($column['COLUMN_NAME']=='nextactiondate'){
                            $result = $this->connection->insert('ox_field', array(
                                'name'=> 'nextactiondate',
                                'text'=> 'Next Action Date',
                                'formid'=> $form['id'],
                                'data_type'=> 'datetime',
                                'required'=> 1,
                                'sequence'=> 1,
                            ));
                        }
                        if($column['COLUMN_NAME']=='enddate'){
                            $result = $this->connection->insert('ox_field', array(
                                'name'=> 'enddate',
                                'text'=> 'End Date',
                                'formid'=> $form['id'],
                                'data_type'=> 'datetime',
                                'required'=> 1,
                                'sequence'=> 1,
                            ));
                        }
                        if($column['COLUMN_NAME']=='description'){
                            $result = $this->connection->insert('ox_field', array(
                                'name'=> 'description',
                                'text'=> 'Description',
                                'formid'=> $form['id'],
                                'data_type'=> 'textarea',
                                'required'=> 1,
                                'sequence'=> 1,
                            ));
                        }
                        if($column['COLUMN_NAME']=='parentinstformid'){
                            $result = $this->connection->insert('ox_field', array(
                                'name'=> 'parentform',
                                'text'=> 'Parent',
                                'formid'=> $form['id'],
                                'data_type'=> 'select',
                                'required'=> 1,
                                'sequence'=> 1,
                            ));
                        }
                        $fieldId = $this->connection->lastInsertId();
                        echo "Added field ".$column['COLUMN_NAME']." with field Id ".$fieldId."\n";
                        $insertQuery = $this->connection->executeUpdate("INSERT INTO ox_file_attribute (`fileid`, `fieldid`, `org_id`, `fieldvalue`)
                            SELECT `id`, '".$fieldId."', `orgid`, `".$column['COLUMN_NAME']."`
                            FROM instanceforms WHERE formid=".$form['id'].";");
                        echo $insertQuery." Completed"."\n";
                    }
                }
            }
        }
    }
    protected function convertOptionsArray($options){
        if($optionslist[0]=="$"){
            $optionArray = $this->connection->executeQuery("SELECT value from metalist WHERE name='".str_replace("$", "", $optionslist)."'");
            return $this->convertListToArray($optionArray[0]['value']);
        } else if($optionslist[0]=="/"){
            return json_encode(array('url'=>$options));
        } else {
            return $this->convertListToArray($options);
        }
    }
    protected function convertListToArray($optionslist){
        $listoptions = explode('|', $optionslist);
        if($listoptions){
            $options = array();
            foreach ($listoptions as $option) {
                $keyandoption = explode('=>', $option);
                if($keyandoption[0]&&$keyandoption[1]){
                    $options[trim($keyandoption[0])] = trim(str_replace("'", "", $keyandoption[1]));
                } else {
                    if($keyandoption[1]){
                        $options[0] = trim($keyandoption[1]);
                    }
                }
            }
            return json_encode(array('data'=>$options));
        } else {
            return json_encode(array('data'=>$optionslist));
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $sql = "CREATE TABLE  IF NOT EXISTS fields ( 
        `id` INT( 11 ) AUTO_INCREMENT NOT NULL ,
        `name` VARCHAR( 100 ) NOT NULL ,
        `text` VARCHAR( 400 ) NOT NULL ,
        `columnname` VARCHAR( 1000 ) ,
        `helpertext` VARCHAR( 150 ) ,
        `type` VARCHAR( 30 ) ,
        `options` VARCHAR( 10000 ) ,
        `color` VARCHAR( 1000 ) ,
        `regexpvalidator` VARCHAR( 100 ) ,
        `validationtext` VARCHAR( 250 ) ,
        `specialvalidator` VARCHAR( 50 ) ,
        `expression` VARCHAR( 1000 ) ,
        `condition` VARCHAR( 250 ) ,
        `premiumname` VARCHAR( 50 ) ,
        `xflat_parameter` INT( 2 ) NOT NULL DEFAULT '0',
        `esign_parameter` INT( 11 ) NOT NULL DEFAULT '0' COMMENT 'this field will be used in esign api',
        `field_type` VARCHAR( 100 ) NOT NULL DEFAULT 'config',
        `category` VARCHAR( 1000 ) NOT NULL DEFAULT '1',
        PRIMARY KEY ( `id` ) )
        ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
        $this->addSql($sql);
        $this->addSql("ALTER TABLE fields ADD UNIQUE INDEX ix_name (name)");
        $sql = "INSERT INTO fields (`name`, `text`, `helpertext`, 
        `type`,`options`, `color`, `regexpvalidator`,
        `validationtext`, `specialvalidator`, 
        `expression`, `condition`, `premiumname`, 
        `xflat_parameter`, `esign_parameter`, 
        `field_type`, `category`) 
        SELECT distinct mf.`name`, `text`, `helpertext`, 
        `type`, `options`, color, regexpvalidator, 
        validationtext, specialvalidator, expression,
        `condition`, premiumname, xflat_parameter,
        esign_parameter, field_type, category from metafields mf
        inner join (select min(id) as id, `name` from metafields group by `name`) uf on uf.id = mf.id";
        $this->addSql($sql);
        $this->addSql("DROP TABLE ox_form");
        $this->addSql("DROP TABLE ox_metafield");
        $this->addSql("DROP TABLE ox_field");
        $this->addSql("DROP TABLE ox_file");
        $this->addSql("DROP TABLE ox_file_attribute");
    }
}
