<?php
namespace Mlet\Service;

use Oxzion\Service\AbstractService;
use Mlet\Model\MletTable;
use Mlet\Model\Mlet;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;

class MletService extends AbstractService{
    private $table;

    public function __construct($config, $dbAdapter, MletTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function getMlets() { 
        $data=$this->table->fetchAll(['orgid' => AuthContext::get(AuthConstants::ORG_ID)])->toArray();
        return $data;
    }
}
?>