<?php

namespace Alert\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;

class AlertTable extends ModelTable {

    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data) {
        return $this->internalSave($data->toArray());
    }

    public function getAlerts($avatar, $avatarGroupList) {
        $select = $this->sql->select()
                ->from('ox_alert')
                ->columns(array("*"))
                ->join('ox_alert_group_mapper', 'ox_alert.id = ox_alert_group_mapper.alert_id', Array("*"))
                ->where(array('ox_alert_group_mapper.group_id IN' => $avatarGroupList));
        return $data = $this->queryExecute($select);
    }

}
