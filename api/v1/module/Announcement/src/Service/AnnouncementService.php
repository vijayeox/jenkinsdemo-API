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

    public function createAnnouncement($data){
        $form = new Announcement();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        if($data['start_date'] == null){
            //TODO set start_date to today
        }
        //TODO set createdDate 
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
            $id = $this->getLastInsertValue();
            $form->id = $id;
            if(isset($data['groups'])){
                $affected = $this->insertAnnouncementForGroup($id,$data['groups']);
                if($affected != count($data['groups'])) {
                    $this->rollback;
                    return 0;
                }
            } else {
                //TODO handle this case properly
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
        }

        return $count;
    }
    protected function insertAnnouncementForGroup($announcementId, $groups){
        $rowsAffected = 0;
        foreach ($groups as $key => $id) {
            $sql = $this->getSqlObject();
            $insert = $sql->insert('ox_announcement_group_mapper');
            $data = array('group_id'=>$id,'announcement_id'=>$announcementId);
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
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array(),'left')
                ->join('groups_avatars', 'ox_announcement_group_mapper.group_id = groups_avatars.groupid')
                ->where(array('groups_avatars.avatarid' => $userId));

        return $this->executeQuery($select)->toArray();
    }
}
?>