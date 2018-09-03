<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\Organization;

class OrganizationTable extends ModelTable {
    public function __construct() {
    	$this->tablename = 'organizations';
        parent::__construct(new Organization());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}