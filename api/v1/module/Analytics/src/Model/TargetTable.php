<?php

namespace Analytics\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;

class TargetTable extends ModelTable {

    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    // The following save function will be deprecated. It will be replaced by save2
    public function save(Entity $data) {
        return $this->internalSave($data->toArray());
    }

    public function save2(Entity $data) {
        return $this->internalSave2($data->toArray());
    }

}
