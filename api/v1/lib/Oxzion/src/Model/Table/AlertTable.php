<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Db\Config;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\Alert;

class AlertTable extends ModelTable {
    public function __construct() {
		$this->tablename = 'alerts';
        parent::__construct(new Alert());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}