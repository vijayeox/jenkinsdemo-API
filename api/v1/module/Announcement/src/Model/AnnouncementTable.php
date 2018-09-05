<?php

namespace Announcement\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;

class AnnouncementTable extends ModelTable {

    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Model $data) {
        return $this->internalSave($data->toArray());
    }

    public function getAnnouncements($avatar, $avatarGroupList) {
        $select = $this->sql->select()
                ->from('ox_annoucement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_annoucement.id = ox_announcement_group_mapper.announcement_id', Array("*"))
                ->where(array('ox_announcement_group_mapper.group_id IN' => $avatarGroupList));
        return $data = $this->queryExecute($select);
    }

}
