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
//        $params = $this->params()->fromRoute();
//        $type = $params['type'];
//        echo "Check<pre/>";
//        print_r($params);
//        exit;
//        $avatar = $this->currentAvatarObj;
//        echo "<pre/>";
//        print_r($avatar);
//        exit;
        $getAnnoucements = $this->getAnnouncements("436", Array("1463", "333", "912"));
        if (is_null($getAnnoucements)) {
//            return $this->getErrorResponse("Entity not found for id - $avatar->id", 404);
        }
        return $this->getSuccessResponseWithData($getAnnoucements);
    }

    public function getAnnouncements($avatar, $avatarGroupList) {
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', Array("*"))
                ->where(array('ox_announcement_group_mapper.group_id' => $avatarGroupList));
        return $data = Query::queryExecute($select,$sql,$this->dbAdapter);
    }

}
