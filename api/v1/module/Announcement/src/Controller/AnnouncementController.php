<?php
namespace Announcement\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\Announcement;
use Oxzion\Model\Table\AnnouncementTable;
use Oxzion\Controller\AbstractApiController;

class AnnouncementController extends AbstractApiController {

    public function __construct(AnnouncementTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Announcement::class);
		$this->setIdentifierName('announcementId');
	}
}