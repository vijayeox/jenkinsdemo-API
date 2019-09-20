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

    public function generateCSVData($storedProcedureName, $orgId, $appId, $appName, $fileName)
    {
        $filePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/data/";
        $archivePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/archive/"; //The path to the folder Ex: /clients/<App name>/data/migrations/app/<appname>/archive/

        $dataSet = array_diff(scandir($filePath), array(".", ".."));
        $filePath = $filePath . $fileName;
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
    public function uploadCSVData($storedProcedureName, $orgId, $appId, $appName, $srcURL, $fileName)
    {
        // $host = "oxzion.com";
        // $userID = "rakshith@oxzion.com";
        // $password = "sftp@rakshith";

//This code will come from the deployment descriptor. I have kept it here for now.
        $host = "206.107.76.164";
        $userID = "vbinsurance";
        $password = "<<InsureName>>";

        $filePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/data/";
        $f_pointer = fopen($filePath, "r");
        $ftp_server = $host;
        $ftp_conn = ftp_ssl_connect($ftp_server) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, $userID, $password);
        // ftp_set_option($ftp_conn, 1, true);
        // echo "USEPASVADDRESS Value: " . ftp_get_option($ftp_conn, USEPASVADDRESS) ? '1' : '0';exit;
        // ftp_pasv($ftp_conn, true);
        ftp_chdir($ftp_conn, "/");

        if ($login) {
            echo "<br>logged in successfully!";
            $contents = ftp_nlist($ftp_conn, ".");
            print_r($contents);exit;
            foreach ($contents as $value) {
                if ($fileName === $value) {
                    // $result = ftp_fget($ftp_conn, $f_pointer, $value, FTP_BINARY);
                    if (ftp_get($ftp_conn, $filePath . $fileName, $value, FTP_BINARY)) {
                        echo "Successfully written to $fileName \n";
                    } else {
                        echo "There was a problem \n";
                    }
                    echo $value . "<br/>";
                }
            }
        } else {
            echo "Can't login to remote server.";
            return 0;
        }
        if (ftp_close($ftp_conn)) {
            echo "<br>Connection closed Successfully!";
        }
        return 1;
    }

}
