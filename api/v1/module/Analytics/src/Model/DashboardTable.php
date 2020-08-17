<?php

namespace Analytics\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;

class DashboardTable extends ModelTable {

    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data) {
        return $this->internalSave($data->toArray());
    }

    // public function save2(Entity &$data) {
    //     $temp = $data->toArray();
    //     $count = $this->internalSave2($temp);
    //     return $temp;
    // }
    public function save2(Entity $data) {
        return $this->internalSave2($data->toArray());
    }
}

