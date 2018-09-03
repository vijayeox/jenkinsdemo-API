<?php
namespace Oxzion\Model\Table;

use Oxzion\Db\ModelTable;
use Oxzion\Db\Config;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\Avatar;

class AvatarTable extends ModelTable {
	public function __construct() {
		$this->tablename = 'avatars';
		parent::__construct(new Avatar());
	}

    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}