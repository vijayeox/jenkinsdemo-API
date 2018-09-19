<?php
namespace Screen\Service;

use Oxzion\Service\AbstractService;
use Screen\Model\ScreenwidgetTable;
use Screen\Model\Screenwidget;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;

class ScreenwidgetService extends AbstractService{
    private $table;

    public function __construct($config, $dbAdapter, ScreenwidgetTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }


    public function getWidgets($screenId) {
            $data=$this->table->fetchAll(['userid' => AuthContext::get(AuthConstants::USER_ID),'screenid' =>$screenId])->toArray();
            return $data;
    }
}
?>