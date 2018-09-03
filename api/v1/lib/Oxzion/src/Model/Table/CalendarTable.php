<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Model;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity\Calendar;

class CalendarTable extends ModelTable {
    public function __construct() {
    	$this->tablename = 'operatingrhythm';
        parent::__construct(new Calendar());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}