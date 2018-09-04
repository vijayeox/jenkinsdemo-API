<?php
namespace Announcement\Controller;

use Zend\Log\Logger;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Oxzion\Controller\AbstractApiController;

class AnnouncementController extends AbstractApiController {

    public function __construct(AnnouncementTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Announcement::class);
		$this->setIdentifierName('announcementId');
	}
}