<?php
namespace Screen\Service;

use Bos\Service\AbstractService;
use Screen\Model\ScreenwidgetTable;
use Screen\Model\Screenwidget;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Oxzion\ValidationException;

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
    
    public function update($id,&$data){
        $form = new Screenwidget();
        $obj = $this->table->get($id,array());
        if ($obj->userid!=AuthContext::get(AuthConstants::USER_ID)) {
            $validationException = new ValidationException();
            $validationException->setErrors(['userid' => 'Access Denied. Invalid Userid']);
            throw $validationException;
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

    public function delete($id) {
        
        $obj = $this->table->get($id,array());
        if ($obj) {
            if ($obj->userid!=AuthContext::get(AuthConstants::USER_ID)) {
                $validationException = new ValidationException();
                $validationException->setErrors(['userid' => 'Access Denied. Invalid Userid']);
                throw $validationException;
            }
            try{
                $this->table->delete($id);
                return 1;
            }catch(Exception $e){
                return 0;
            }
            return 0;
        }
    }
    
}
?>