<?php

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;
use App\Service\ImportService;
use Oxzion\Utils\FileUtils;
use Zend\Log\Logger;
use Oxzion\Error\ErrorHandler;

class Import implements AppDelegate
{
    private $logger;
    private $persistenceService;
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    private $adapter;

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->persistenceService = $persistenceService;
        try
        {
            $datavalidate = $this->checkArrayEmpty($data);
            if($datavalidate === "0") {
                return Array("status" => "Error", "data"=>$data);
            }
            $uploadData = $this->uploadCSVData($data['stored_procedure_name'], $data['org_id'], $data['app_id'], $data['app_name'], $data['src_url'], $data['file_name'], $data["host"], $data["user_id"], $data["password"]);

            $returnData = $this->generateCSVData($data['stored_procedure_name'], $data['org_id'], $data['app_id'], $data['app_name'], $data['file_name']);

            $filePath = array(dirname(__dir__) . "/import/data/");

            if ($returnData == 2) {
                return 2;
            }
            if ($returnData == 3) {
                return 3;
            }
        } catch (Exception $e) {
            throw $e;
            $this->logger->err(__CLASS__ . "->" . $e->getMessage());
            return array(0);
        }
        return $data;
    }


    public function generateCSVData($storedProcedureName, $orgId, $appId, $appName, $fileName)
    {
        $fileFolder = dirname(__dir__) . "/import/data/";
        $archivePath = dirname(__dir__) . "/import/archive/"; //The path to the folder Ex: /clients/<App name>/data/migrations/app/<appname>/archive/

        $dataSet = array_diff(scandir($fileFolder), array(".", ".."));
        $filePath = $fileFolder . $fileName;
        if (!file_exists($filePath)) {
            return 2;
        }

        $f_pointer = fopen($filePath, "r");
        while (!feof($f_pointer)) {
            $ar = fgetcsv($f_pointer);
            if (!empty($ar)) {
                $listStr = implode(",", $ar);
                $data = $this->importCSVData($storedProcedureName, $ar);
                $importData[] = $data;
            }
        }
        if (is_dir($archivePath)) {
            FileUtils::copy($filePath, $fileName, $archivePath);
        } else {
            return 3;
        }
        return 1;
    }

    public function importCSVData($storedProcedureName, $data)
    {
        $this->param = "";
        foreach ($data as $val) {
            $this->param .= "'" . trim($val) . "', ";
        }
        $this->param = rtrim($this->param, ", ");
        $queryString = "call " . $storedProcedureName . "(" . $this->param . ")";
        return $this->persistenceService->runGenericQuery($queryString);
    }

    // Code is not in use untill we get the download feature that we need to get from the clients
    public function uploadCSVData($storedProcedureName, $orgId, $appId, $appName, $srcURL, $fileName, $host, $userId, $password)
    {
        // $host = "oxzion.com";
        // $userId = "rakshith@oxzion.com";
        // $password = "sftp@rakshith";

//This code will come from the deployment descriptor. I have kept it here for now.
        // $host = "206.107.76.164";
        // $userId = "vbinsurance";
        // $password = "<<InsureName>>";

        $filePath = dirname(__dir__) . "/import/data/";
        $f_pointer = fopen($filePath, "r");
        // echo $filePath . $fileName;exit;
        $ftp_server = $host;
        $ftp_conn = ftp_ssl_connect($ftp_server) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, $userId, $password);
        // ftp_set_option($ftp_conn, 1, true);
        // echo "USEPASVADDRESS Value: " . ftp_get_option($ftp_conn, USEPASVADDRESS) ? '1' : '0';exit;
        ftp_pasv($ftp_conn, true);
        ftp_chdir($ftp_conn, "/");


        try {
            if ($login) {
                echo "<br>logged in successfully!";
                $contents = ftp_nlist($ftp_conn, ".");
                if(!empty($contents)) {
                    foreach ($contents as $value) {
                        if ($fileName === $value) {
                    // $result = ftp_fget($ftp_conn, $f_pointer, $value, FTP_BINARY);
                            if (ftp_get($ftp_conn, $filePath . $fileName, $value, FTP_BINARY)) {
                                echo "Successfully written to $fileName \n";
                            } else {
                                return $this->getFailureResponse("Import Aborted, please make sure your file is in the correct format", $fileName);
                                // return $fileName;
                            }
                        }
                    }
                } else {
                    echo "There are no files in the folder";
                    return 0;
                }
            } else {
                echo "Can't login to remote server.";
                return 0;
            }
            if (ftp_close($ftp_conn)) {
                echo "<br>Connection closed Successfully!";
            }
        } catch(Exception $e) {
           $this->logger->err(__CLASS__ . "->" . $e->getMessage());
           return $this->getFailureResponse("Failed to create a new entity", $data);
           // return 0;
       }
       return 1;
   }

   function checkArrayEmpty($array = array()) {
    foreach ($array as $element) {
        if (empty($element)) {
            return "0";
        }
    }
    return "1";
}
}
