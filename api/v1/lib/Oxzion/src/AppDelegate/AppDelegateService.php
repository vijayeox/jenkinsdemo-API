<?php
namespace Oxzion\Rule;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
class AppDelegateService extends AbstractService
{
    private $fileExt = ".php";

    public function __construct($config, $dbAdapter)
    {
        parent::__construct($config, $dbAdapter);
        $this->delegateDir = $this->config['RULE_FOLDER'];
        if (!is_dir($this->delegateDir)) {
            mkdir($this->delegateDir, 0777, true);
        }
    }

    public function execute($appId, $className, $dataArray=array(), Persistence $persistenceService=null)
    {
        try {
            $result = $this->delegateFile($appId, $className);
            if ($result) {
                $obj = new $className;
                $output = $obj->execute($dataArray, $persistenceService);
                return $output;
            }
            return false;
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        return false;
    }
    
    private function delegateFile($appId, $className)
    {
        $file = $className.$this->fileExt;
        $path = $this->delegateDir.$appId."/".$file;
        if ((file_exists($path))) {
            // include $path;
            require_once($path);
        } else {
            return false;
        }
        return true;
    }
}
