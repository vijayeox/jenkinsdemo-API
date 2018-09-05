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
    	return $this->internalSave($data->toArray());
    }

    public function getAnnouncements($avatar, $avatarGroupList) {
		$sql = new Sql($this->adapter);
		$select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', Array("*"))
                ->where(array('ox_announcement_group_mapper.group_id' => $avatarGroupList));
        return $data = $this->queryExecute($select,$sql);
    }
}