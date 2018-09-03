<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Db\Config;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\ModuleCategory;

class ModuleCategoryTable extends ModelTable {
    public function __construct() {
		$this->tablename = 'modulecategories';
        parent::__construct(new ModuleCategory());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}