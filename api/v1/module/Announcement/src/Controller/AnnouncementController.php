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
    
    public function getList() {
       $params = $this->params()->fromRoute();
        return $this->getSuccessResponseWithData($this->table->getAnnouncements("436", Array("1463", "333", "912")));
    }


}
