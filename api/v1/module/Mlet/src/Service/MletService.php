<?php
namespace Mlet\Service;

use Bos\Service\AbstractService;
use Mlet\Model\MletTable;
use Mlet\Model\Mlet;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Exception;

class MletService extends AbstractService{
    private $table;
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, MletTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

 	/**
    * GET List Mlet Service
    * @method GET
    * @return array Returns a JSON Response list of Mlet based on Access.
    */
    public function getMlets() { 
        $data=$this->table->fetchAll(['orgid' => AuthContext::get(AuthConstants::ORG_ID)])->toArray();
        return $data;
    }
}
?>