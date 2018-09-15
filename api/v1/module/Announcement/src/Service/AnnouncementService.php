<?php
namespace Announcement\Service;

use Oxzion\Service\AbstractService;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;

class AnnouncementService extends AbstractService{
    private $table;

    public function __construct($config, $dbAdapter, AnnouncementTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createAnnouncement(&$data){
        $form = new Announcement();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['start_date'] = $data['start_date']?$data['start_date']:date('Y-m-d H:i:s');
        $data['status'] = $data['status']?$data['status']:1;
        $data['end_date'] = $data['end_date']?$data['end_date']:date('Y-m-d H:i:s',strtotime("+7 day"));
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
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            if(isset($data['groups'])){
                $affected = $this->insertAnnouncementForGroup($id,$data['groups']);
                if($affected != count($data['groups'])) {
                    $this->rollback();
                    return 0;
                }
            } else {
                //TODO handle this case properly
            }
            $this->commit();
        }catch(Exception $e){
            // print_r($e);exit;
            $this->rollback();
            return 0;
        }

        return $count;
    }
    public function updateAnnouncement($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $originalArray = $obj->toArray();
        $data['org_id'] = $originalArray['org_id'];
        $data['created_date'] = $originalArray['created_date'];
        $data['created_id'] = $originalArray['created_id'];
        $form = new Announcement();
        $data['id'] = $id;
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
            $data['id'] = $id;
            if(isset($data['groups'])){
                $groupsUpdated = $this->updateGroups($id,$data['groups']);
                if(!$groupsUpdated) {
                    $this->rollback();
                    return 0;
                }
            } else {
                //TODO handle this case properly
            }
            $this->commit();
        }catch(Exception $e){
            // print_r($e);exit;
            $this->rollback();
            return 0;
        }
        return $count;
    }

    protected function updateGroups($announcementId,$groups){
        $oldGroups = array_column($this->getGroupsByAnnouncement($announcementId), 'group_id');
        $newGroups = array_column($groups,'id');
        $groupsAdded = array_diff($newGroups,$oldGroups);
        $groupsRemoved = array_diff($oldGroups,$newGroups);
        $insertGroups = array();
        foreach ($groupsAdded as $key => $value) {
            $insertGroups[$key]['id'] = $value;
        }
        $result['insert'] = $this->insertAnnouncementForGroup($announcementId,$insertGroups);
        if($result['insert']!=count($groupsAdded)){
            return 0;
        }
        $result['delete'] = $this->deleteGroupsByAnnouncement($announcementId,$groupsRemoved);
        if($result['delete']!=count($groupsRemoved)){
            return 0;
        }
        return 1;
    }
    protected function deleteGroupsByAnnouncement($announcementId,$groupIdList){
        $rowsAffected = 0;
        foreach ($groupIdList as $key => $groupId) {
            $sql = $this->getSqlObject();
            $delete = $sql->delete('ox_announcement_group_mapper');
            $delete->where(['announcement_id' => $announcementId,'group_id' => $groupId]);
            $result = $this->executeUpdate($delete);
            if($result->getAffectedRows() == 0){
                break;
            }
            $rowsAffected++; 
        }
        return $rowsAffected;
    }
    protected function getGroupsByAnnouncement($announcementId){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement_group_mapper')
        ->columns(array("group_id","announcement_id"))
        ->where(array('ox_announcement_group_mapper.announcement_id' => $announcementId));
        return $this->executeQuery($select)->toArray();
    }
    protected function insertAnnouncementForGroup($announcementId, $groups){
        $rowsAffected = 0;
        foreach ($groups as $key => $id) {
            $sql = $this->getSqlObject();
            $insert = $sql->insert('ox_announcement_group_mapper');
            $data = array('group_id'=>$id['id'],'announcement_id'=>$announcementId);
            $insert->values($data);
            $result = $this->executeUpdate($insert);
            if($result->getAffectedRows() == 0){
                break;
            }
            $rowsAffected++;            
        }
        return $rowsAffected;
    }


    public function deleteAnnouncement($id){
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->delete($id, ['org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $sql = $this->getSqlObject();
            $delete = $sql->delete('ox_announcement_group_mapper');
            $delete->where(['announcement_id' => $id]);
            $result = $this->executeUpdate($delete);
            if($result->getAffectedRows() == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
        }
        
        return $count;
    }

    public function getAnnouncements() {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array('group_id','announcement_id'),'left')
                ->join('groups_avatars', 'ox_announcement_group_mapper.group_id = groups_avatars.groupid',array('groupid','avatarid'),'left')
                ->where(array('groups_avatars.avatarid' => $userId));
        return $this->executeQuery($select)->toArray();
    }
}
?>