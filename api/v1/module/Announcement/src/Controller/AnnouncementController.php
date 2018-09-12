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
	private $dbAdapter;
    public function __construct(AnnouncementTable $table, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Announcement::class);
        $this->setIdentifierName('announcementId');
		$this->dbAdapter = $dbAdapter;
    }
    public function create($data){
        $data['org_id'] = $this->authContext->getOrgId();
        // if(isset($data['group_id'])){
            
        // }
        return parent::create($data);
    }
    
    public function getList() {
       $params = $this->params()->fromRoute();
//        $type = $params['type'];
//        echo "Check<pre/>";
//        print_r($params);
//        exit;
//        $avatar = $this->currentAvatarObj;
//        echo "<pre/>";
//        print_r($avatar);
//        exit;
        return $this->getSuccessResponseWithData($this->table->getAnnouncements($this->authContext->getId(), array_column($this->authContext->getGroups(),'id')));
    }


}
