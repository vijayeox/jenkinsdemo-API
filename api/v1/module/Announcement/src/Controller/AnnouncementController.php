<?php

namespace Announcement\Controller;

use Zend\Log\Logger;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\Query;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class AnnouncementController extends AbstractApiController {

    public function __construct(AnnouncementTable $table, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Announcement::class);
        $this->setIdentifierName('announcementId');
    }
    public function create($data){
        $response = $this->table->createAnnouncement($data,$this->authContext);
        if(isset($response['error'])){
            return $this->getErrorResponse($response['response'],$response['statusCode'], $response['data']);
        }
        return $this->getSuccessResponseWithData($response['data'],$response['statusCode']);
    }
    
    public function getList() {
       $params = $this->params()->fromRoute();
        return $this->getSuccessResponseWithData($this->table->getAnnouncements($this->authContext->getId(), array_column($this->authContext->getGroups(),'id')));
    }
    public function delete($id){
        $response = $this->table->deleteAnnouncement($id,$this->authContext);
        if(isset($response['error'])){
            return $this->getErrorResponse($response['response'],$response['statusCode'], $response['data']);
        }
        return $this->getSuccessResponse();
    }


}
