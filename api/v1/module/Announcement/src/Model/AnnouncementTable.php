<?php

namespace Announcement\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Oxzion\Controller\ValidationResult;

class AnnouncementTable extends ModelTable {
    protected $tableGateway;
	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
        $this->tableGateway = $tableGateway;
    }
    protected function validate($model){
        return new ValidationResult(ValidationResult::SUCCESS);
    }
    public function save(Entity $data){
        $data = $data->toArray();
        $data['status'] = $data['status']?$data['status']:1;
        $data['start_date'] = $data['start_date']?$data['start_date']:date('Y-m-d H:i:s');
        $data['end_date'] = $data['end_date']?$data['end_date']:date('Y-m-d H:i:s');
    	return $this->internalSave($data);
    }
    protected function getAdapter(){
    	return $this->adapter;
    }
    public function getAnnouncements($avatar, $avatarGroupList) {
		$sql = new Sql($this->getAdapter());
		$select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array(),'left')
                ->where(array('ox_announcement_group_mapper.group_id' => $avatarGroupList));
        return $data = $this->queryExecute($select,$sql);
    }
    public function createAnnouncement($data,$__authContext){
        $form = new Announcement();
        $form->exchangeArray($data);
        $validationResult = $this->validate($form);
        if(! $validationResult->isValid()){
            return array("response"=>$validationResult->getMessage(),'statusCode'=> 404, 'data'=>$data,"error"=>1);
        }
        $count = $this->save($form);
        if($count == 0){
            return array("response"=>"Failed to create a new entity",'statusCode'=> 200, 'data'=>$data,"error"=>1);
        }
        $id = $this->getLastInsertValue();
        $form->id = $id;
        $data['org_id'] = $__authContext->getOrgId();
        if(isset($data['group_id'])){
            $this->insertAnnouncementForGroup($id,$data['group_id']);
            return array('statusCode'=> 201, 'data'=>$form->toArray());
        } else {
            return array("response"=>"No groups have been provided",'statusCode'=> 200, 'data'=>$data,"error"=>1);
        }
    }
    protected function insertAnnouncementForGroup($announcementId,$groupsList){
        $ids = explode(",", $groupsList);
        $data = array();
        foreach ($ids as $id) {
            $sql = $this->getSqlObject();
            $insert = $sql->insert('ox_announcement_group_mapper');
            $data = array('group_id'=>$id,'announcement_id'=>$announcementId);
            $insert->values($data);
            $selectString = $sql->getSqlStringForSqlObject($insert);
            $results[] = $this->getAdapter()->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        }
        return $results;
    }
    public function deleteAnnouncement($id,$__authContext){
        $count = $this->delete($id, null);
        if($count == 0){
            return array("response"=>"No entity found for id - $id",'statusCode'=> 404, 'data'=>$data,"error"=>1);
        }
        $sql = $this->getSqlObject();
        $delete = $sql->delete('ox_announcement_group_mapper');
        $delete->where('announcement_id='.$id);
        $selectString = $sql->getSqlStringForSqlObject($delete);
        $results[] = $this->getAdapter()->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        return;
    }
}