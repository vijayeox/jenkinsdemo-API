<?php
namespace Announcement\Service;

use Oxzion\Service\AbstractService;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\FileService;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Exception;

class AnnouncementService extends AbstractService{
    const ANNOUNCEMENT_FOLDER = "/announcements/";

    private $table;

    public function __construct($config, $dbAdapter, AnnouncementTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    protected function getAnnouncementFolder($id){
        return $this->config['DATA_FOLDER']."organization/".AuthContext::get(AuthConstants::ORG_ID).self::ANNOUNCEMENT_FOLDER.$id;
    }
    public function getFileName($file){
        $fileName = explode('-', $file,2);
        return $fileName[1];
    }
    public function createAnnouncement(&$data){
        $form = new Announcement();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['start_date'] = isset($data['start_date'])?$data['start_date']:date('Y-m-d H:i:s');
        $data['status'] = $data['status']?$data['status']:1;
        $data['end_date'] = isset($data['end_date'])?$data['end_date']:date('Y-m-d H:i:s',strtotime("+7 day"));
        $data['created_date'] = date('Y-m-d H:i:s');
        if(isset($data['file'])){
            $file = $data['file'];
            $data['media_location'] = $this->getFileName($data['file']);
            unset($data['file']);
        }
        if(isset($data['groups'])){
            $groups = json_decode($data['groups'],true);
            unset($data['groups']);
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
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            if(isset($groups)){
                $affected = $this->insertAnnouncementForGroup($id,$groups);
                if(is_string($groups) && $affected != count($groups)) {
                    $this->rollback();
                    return 0;
                }
            }
            $this->commit();
            FileService::renameFile($this->config['DATA_FOLDER']."temp/".$file,$this->getAnnouncementFolder($id)."/".$data['media_location']);
        }catch(Exception $e){
            // print_r($e->getMessage());exit;
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
        $form = new Announcement();
        $data = array_merge($originalArray, $data);
        $data['id'] = $id;
        if(isset($data['groups'])){
            $groups = json_decode($data['groups'],true);
            unset($data['groups']);
        }
        if(isset($data['file'])){
            $file = $data['file'];
            $data['media_location'] = $this->getFileName($data['file']);
            unset($data['file']);
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
            $data['id'] = $id;
            if(isset($data['groups'])){
                $groupsUpdated = $this->updateGroups($id,$groups);
                if(!$groupsUpdated) {
                    $this->rollback();
                    return 0;
                }
            } else {
                //TODO handle this case properly
            }
            if(isset($file)){
                $this->moveTempFile($file,$this->getAnnouncementFolder($id)."/".$data['media_location']);
            }
            $this->commit();
        }catch(Exception $e){
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
        if($result['delete']!=count($groupsRemoved)||count($groupsRemoved)==0){
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
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array('group_id','announcement_id'),'left')
                ->join('groups_avatars', 'ox_announcement_group_mapper.group_id = groups_avatars.groupid',array('groupid','avatarid'),'left')
                ->where(array('groups_avatars.avatarid' => AuthContext::get(AuthConstants::USER_ID)));
        return $this->executeQuery($select)->toArray();
    }

    protected function moveTempFile($file,$location){
        FileService::renameFile($this->config['DATA_FOLDER']."temp/".$file,$location);
    }
}
?>