<?php
namespace Organization\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;

class OrganizationTable extends ModelTable {
    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Model $data){
    	return $this->internalSave($data->toArray());
    }
}