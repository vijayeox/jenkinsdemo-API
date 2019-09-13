<?php
namespace Screen\Service;

use Oxzion\Service\AbstractService;
use Screen\Model\ScreenTable;
use Screen\Model\Screen;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;

class ScreenService extends AbstractService{
    private $table;

    public function __construct($config, $dbAdapter, ScreenTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

}
?>