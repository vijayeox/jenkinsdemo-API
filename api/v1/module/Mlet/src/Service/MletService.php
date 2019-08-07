<?php
namespace Mlet\Service;

use Oxzion\Service\AbstractService;
use Mlet\Model\MletTable;
use Mlet\Model\Mlet;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Analytics\AnalyticsEngine;
use Exception;

class MletService extends AbstractService
{
    private $table;
    private $analyticsEngine;
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, MletTable $table, AnalyticsEngine $analyticsEngine)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->analyticsEngine = $analyticsEngine;
    }

    /**
    * GET List Mlet Service
    * @method GET
    * @return array Returns a JSON Response list of Mlet based on Access.
    */
    public function getMlets()
    {
        $data=$this->table->fetchAll(['orgid' => AuthContext::get(AuthConstants::ORG_ID)])->toArray();
        return $data;
    }


    public function getResult($id, $para)
    {
        $parameters = array();
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $mlet = $obj->toArray();
        $appId = $mlet['appid'];
        if ($mlet['parameters']) {
            $parameters = json_decode($mlet['parameters'], true);
        }
        if ($para) {
            $parameters = array_replace($parameters, $para);
        }
        $type = ($mlet['doctype'])?$mlet['doctype']:null;
        $result = $this->analyticsEngine->runQuery($appId, $type, $parameters);
        return $result;
    }
}
