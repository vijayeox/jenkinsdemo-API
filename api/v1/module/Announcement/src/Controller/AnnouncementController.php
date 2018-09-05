<?php

namespace Announcement\Controller;

use Zend\Log\Logger;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Oxzion\Controller\AbstractApiController;

class AnnouncementController extends AbstractApiController {

    public function __construct(AnnouncementTable $table, Logger $log) {
        parent::__construct($table, $log, __CLASS__, Announcement::class);
        $this->setIdentifierName('announcementId');
    }
    
    public function getList() {
        $alert_obj = new AnnouncementTable();
//        $params = $this->params()->fromRoute();
//        $type = $params['type'];
//        echo "Check<pre/>";
//        print_r($params);
//        exit;
//        $avatar = $this->currentAvatarObj;
//        echo "<pre/>";
//        print_r($avatar);
//        exit;
        $getAnnoucements = $alert_obj->getAnnouncements("436", Array("1463", "333", "912"));
        if (is_null($getAnnoucements)) {
//            return $this->getErrorResponse("Entity not found for id - $avatar->id", 404);
        }
        return $this->getSuccessResponseWithData($getAnnoucements);
    }

}
