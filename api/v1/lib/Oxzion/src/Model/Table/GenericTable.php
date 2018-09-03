<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Db\Config;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\Group;

class GenericTable extends ModelTable {
	public function __construct($table) {
		$this->tablename = $table; 
		parent::__construct(null);
	}
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}