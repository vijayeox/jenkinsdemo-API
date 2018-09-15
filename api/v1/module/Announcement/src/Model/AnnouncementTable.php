<?php

namespace Announcement\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;

class AnnouncementTable extends ModelTable {
    protected $tableGateway;
	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
        $this->tableGateway = $tableGateway;
    }

    public function save(Entity $data){
        $data = $data->toArray();
        $data['status'] = $data['status']?$data['status']:1;
        $data['start_date'] = $data['start_date']?$data['start_date']:date('Y-m-d H:i:s');
        $data['end_date'] = $data['end_date']?$data['end_date']:date('Y-m-d H:i:s',strtotime("+7 day"));
    	return $this->internalSave($data);
    }
    
    
    
}