<?php

namespace Alert\Controller;

use Zend\Log\Logger;
use Alert\Model\AlertTable;
use Alert\Model\Alert;
use Oxzion\Controller\AbstractApiController;

class AlertController extends AbstractApiController {

    public function __construct(AlertTable $table, Logger $log) {
        parent::__construct($table, $log, __CLASS__, Alert::class);
        $this->setIdentifierName('alertId');
    }

    public function getList() {
        $alert_obj = new AlertTable();
//        $params = $this->params()->fromRoute();
//        $type = $params['type'];
//        echo "Check<pre/>";
//        print_r($params);
//        exit;
//        $avatar = $this->currentAvatarObj;
//        echo "<pre/>";
//        print_r($avatar);
//        exit;
        $getAlerts = $alert_obj->getAlerts("436", Array("1463", "333", "912"));
        if (is_null($getAlerts)) {
//            return $this->getErrorResponse("Entity not found for id - $avatar->id", 404);
        }
        return $this->getSuccessResponseWithData($getAlerts);
    }

}
