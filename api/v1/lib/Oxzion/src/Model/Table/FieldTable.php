<?php

namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Model;
use Zend\Db\TableGateway\TableGatewayInterface;

class FieldTable extends ModelTable {

	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Model $data){
    	return $this->internalSave($data->toArray());
    }
}