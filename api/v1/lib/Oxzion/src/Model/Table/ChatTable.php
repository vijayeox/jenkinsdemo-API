<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Model;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity\Chat;

class ChatTable extends ModelTable {
    public function __construct() {
    	$this->tablename = 'cometchat';
        parent::__construct(new Chat());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}