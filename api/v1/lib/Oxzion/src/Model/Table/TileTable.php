<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\Tile;

class TileTable extends ModelTable {
    public function __construct() {
    	$this->tablename = 'statusboxes';
        parent::__construct(new Tile());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}