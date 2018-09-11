<?php

namespace Announcement\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;
use Zend\Db\Sql\Sql;

class AnnouncementTable extends ModelTable {

	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data){
        $data = $data->toArray();
        $data['status'] = $data['status']?$data['status']:1;
        $data['start_date'] = $data['start_date']?$data['start_date']:date('Y-m-d H:i:s');
        $data['end_date'] = $data['end_date']?$data['end_date']:date('Y-m-d H:i:s');
    	return $this->internalSave($data);
    }
    protected function getAdapter(){
    	return $this->adapter;
    }
    public function getAnnouncements($avatar, $avatarGroupList) {
		$sql = new Sql($this->getAdapter());
		$select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array(),'left')
                ->where(array('ox_announcement_group_mapper.group_id' => $avatarGroupList));
        return $data = $this->queryExecute($select,$sql);
    }
}