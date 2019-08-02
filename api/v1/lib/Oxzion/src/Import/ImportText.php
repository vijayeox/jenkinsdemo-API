<?php
namespace Oxzion\Import;

use Oxzion\Service\AbstractService;
use Zend\Db\Adapter\Adapter;

class ImportText extends AbstractService
{
    private $adapter;
    public function __construct($config, $database, $adapter)
    {
        $this->database = $database;
        $dbConfig = $config['db'];
        $dbConfig['database'] = 'mysql';
        $dbConfig['dsn'] = 'mysql:dbname=mysql;host=' . $dbConfig['host'] . ';charset=utf8;username=' . $dbConfig["username"] . ';password=' . $dbConfig["password"] . '';
        $this->mysqlAdapter = new Adapter($dbConfig);
        parent::__construct($config, $adapter);
    }

    //Code to get the file txt file in the form of csv from a folder and import them to the database
    public function extractTextFileToArrayImport($file, $filePath, $columns, $tableName)
    {
        try
        {
            $this->beginTransaction();
            $fieldLength = sizeof($columns);
            $fileDetail = $filePath . $file;
            if (!file_exists($fileDetail)) {
                return 3; //returns 3 if there is no file in the 
            }
            $f_pointer = fopen($fileDetail, "r");
            while (!feof($f_pointer)) {
                $ar = fgetcsv($f_pointer);
                if (!empty($ar)) {
                    $ar1 = array_slice($ar, 0, $fieldLength);
                    foreach ($ar1 as $key => $val) {
                        $data[$columns[$key]] = $val;
                    }
                    $importData[] = $data;
                }
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
            return 4; // Returns 4 if there was any exceptions
        }
        $this->multiInsertOrUpdate("ox_padi_verification_pl", $importData, array());
        return 1;
    }

private function checkFilesInFolder($path) {
    $handle = opendir($path);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
        closedir($handle);
        return 1;
        }
    }
    closedir($handle);
    return 0;
}

}
