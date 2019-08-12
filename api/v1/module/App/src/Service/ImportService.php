<?php
namespace App\Service;

use Oxzion\Service\AbstractService;

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

    public function importCSVData($storedProcedureName, $data)
    {
        $this->param = "";
        foreach($data as $val) {
            $this->param .= "'" . trim($val) . "', ";
        }
        $this->param = rtrim($this->param, ", ");
        //  print_r($this->param);exit;
        $queryString = "call " .$storedProcedureName. "(" . $this->param .")";
        return $this->runGenericQuery($queryString);
    }

}
