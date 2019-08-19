<?php
namespace App\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Utils\FileUtils;

class ImportService extends AbstractService
{

    protected $config;
    protected $workflowService;
    protected $fieldService;
    protected $formService;
    protected $param;

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter)
    {
        parent::__construct($config, $dbAdapter);
    }

    public function generateCSVData($storedProcedureName, $orgId, $appId, $appName)
    {
        $filePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/data/";
        $archivePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/archive/"; //The path to the folder Ex: /clients/hub/data/migrations/app/hub/archive/

        $dataSet = array_diff(scandir($filePath), array(".", ".."));
        $filePath = $filePath . $dataSet[2];
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
            FileUtils::copy($filePath, $dataSet[2], $archivePath);
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
        return $this->runGenericQuery($queryString);
    }

    // Code is not in use untill we get the download feature that we need to get from the clients
    public function uploadCSVData($storedProcedureName, $orgId, $appId, $appName, $srcURL)
    {
        $host = "oxzion.com";
        $userID = "rakshith@oxzion.com";
        $password = "sftp@rakshith";

//This code will come from the deployment descriptor. I have kept it here for now.        
        // $host = "206.107.76.164";
        // $userID = "vbinsurance";
        // $password = "<<InsureName>>";

        $filePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/data/";

        $ftp_server = $host;
        $ftp_conn = ftp_ssl_connect($ftp_server) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, $userID, $password);
        $r = ftp_pasv($ftp_conn, true);
        ftp_chdir($ftp_conn, "/");

        if ($login) {
            echo "<br>logged in successfully!";
            $contents = ftp_nlist($ftp_conn, ".");
            foreach ($contents as $value) {
                // $result = ftp_fget($ftp_conn, $filePath, $value, FTP_BINARY);
                echo $value . "<br/";
            }
            var_dump($contents);exit;
        } else {
            echo "Can't login to remote server.";exit;
        }
        if (ftp_close($ftp_conn)) {
            echo "<br>Connection closed Successfully!";
        }
        return 1;
    }

}
