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


    public function createWidget(&$data){
        $form = new Screenwidget();
        if (!isset($data['userid'])) {
            $data['userid']=AuthContext::get(AuthConstants::USER_ID);
        }
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }

        return $count;
    }

    public function updateWidget(&$data) {
        $form = new Screenwidget();
        if (!isset($data['userid'])) {
            $data['userid']=AuthContext::get(AuthConstants::USER_ID);
        }
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $tableGateway = $this->table->getTableGateway();
            $count=$tableGateway->update($data, ['userid'=>$data['userid'],'screenid' => $data['screenid'],'widgetid' => $data['widgetid']]);
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }
    public function deleteWidget(&$data) {
        $form = new Screenwidget();
        if (!isset($data['userid'])) {
            $data['userid']=AuthContext::get(AuthConstants::USER_ID);
        }
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 1;
        try{
            $tableGateway = $this->table->getTableGateway();
            $count=$tableGateway->delete(['userid'=>$data['userid'],'screenid' => $data['screenid'],'widgetid' => $data['widgetid']]);
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }
    
}
?>