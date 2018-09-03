<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Model;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity\Bookmark;

class BookmarkTable extends ModelTable {
    public function __construct() {
		$this->tablename = 'links';
        parent::__construct(new Bookmark());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}