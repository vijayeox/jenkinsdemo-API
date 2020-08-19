<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\Utils\FileUtils;

class Import extends AbstractAppDelegate
{
    private $persistenceService;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->persistenceService = $persistenceService;
        try
        {
            $datavalidate = $this->checkArrayEmpty($data);
            if ($datavalidate === "0") {
                return array("status" => "Error", "data" => $data);
            }
            $this->uploadCSVData($data['stored_procedure_name'], $data['org_id'], $data['app_id'], $data['app_name'], $data['src_url'], $data['file_name'], $data["host"], $data["user_id"], $data["password"]);

            $this->generateCSVData($data['stored_procedure_name'], $data['org_id'], $data['app_id'], $data['app_name'], $data['file_name']);

            $filePath = array(dirname(__dir__) . "/import/data/");
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
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
            throw new DelegateException("File Doesnot exists in the path" . $filePath, "file.not.exists");
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
            throw new DelegateException("Directory doesnot exists -- " . $archivePath, "directory.not.exists");
        }
        return 1;
    }

    public function importCSVData($storedProcedureName, $data)
    {
        $this->param = "";
        foreach ($data as $val) {
            $val = trim($val);
            $val = $val == '' ? "NULL" : "'" . $val . "'";
            $this->param .= $val . ", ";
        }
        $this->param = rtrim($this->param, ", ");
        $queryString = "call " . $storedProcedureName . "(" . $this->param . ")";
        return $this->persistenceService->runQueryForStoredProcedure($queryString, $storedProcedureName);
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
        try {
            $ftp_conn = ftp_ssl_connect($ftp_server);
        } catch (Exception $e) {
            throw new Exception("Could not connect to $ftp_server", 1);
        }
        $login = ftp_login($ftp_conn, $userId, $password);
        // ftp_set_option($ftp_conn, 1, true);
        // echo "USEPASVADDRESS Value: " . ftp_get_option($ftp_conn, USEPASVADDRESS) ? '1' : '0';exit;
        ftp_pasv($ftp_conn, true);
        ftp_chdir($ftp_conn, "/");

        try {
            if ($login) {
                //echo "<br>logged in successfully!";
                $contents = ftp_nlist($ftp_conn, ".");
                if (!empty($contents)) {
                    foreach ($contents as $value) {
                        if ($fileName === $value) {
                            // $result = ftp_fget($ftp_conn, $f_pointer, $value, FTP_BINARY);
                            if (ftp_get($ftp_conn, $filePath . $fileName, $value, FTP_BINARY)) {
                                //echo "Successfully written to $fileName \n";
                            } else {
                                throw new \Exception("Import Aborted, please make sure your file is in the correct format - " . $fileName);
                                // return $fileName;
                            }
                        }
                    }
                } else {
                    throw new DelegateException("There are no files in the folder", "file.not.found");
                }
            } else {
                throw new DelegateException("Can't login to remote server.", "login.failed");
            }
            if (!ftp_close($ftp_conn)) {
                throw new DelegateException("Can't login to remote server.", "login.failed");
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return 1;
    }

    public function checkArrayEmpty($array = array())
    {
        foreach ($array as $element) {
            if (empty($element)) {
                return "0";
            }
        }
        return "1";
    }
}
