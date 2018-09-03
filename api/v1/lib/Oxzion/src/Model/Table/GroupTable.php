<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Db\Config;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\Group;

class GroupTable extends ModelTable {
	public function __construct() {
		$this->tablename = 'groups'; 
		parent::__construct(new Group());
	}
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}