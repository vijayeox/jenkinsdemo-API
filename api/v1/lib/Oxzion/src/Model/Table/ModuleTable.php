<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\App;

class ModuleTable extends ModelTable {
    public function __construct() {
		$this->tablename = 'modules';
        parent::__construct(new App());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}