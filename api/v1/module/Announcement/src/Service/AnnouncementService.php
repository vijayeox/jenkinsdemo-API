<?php
namespace Announcement\Service;

use Bos\Service\AbstractService;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
/**
 * Announcement Service
 */
class AnnouncementService extends AbstractService{
    /**
    * @ignore ANNOUNCEMENT_FOLDER
    */
    const ANNOUNCEMENT_FOLDER = "/announcements/";
    /**
    * @ignore table
    */
    private $table;
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, AnnouncementTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    /**
    * Create Announcement Service
    * @param array $data Array of elements as shown</br>
    * <code> name : string,
    *        status : string,
    *        description : string,
    *        start_date : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *        end_date : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *        media_type : string,
    *        media_location : string,
    *        groups : [{'id' : integer}.....multiple*],
    * </code>
    * @return integer 0|$id of Announcement Created
    */
    public function createAnnouncement(&$data){
        $form = new Announcement();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['start_date'] = isset($data['start_date'])?$data['start_date']:date('Y-m-d H:i:s');
        $data['status'] = $data['status']?$data['status']:1;
        $data['end_date'] = isset($data['end_date'])?$data['end_date']:date('Y-m-d H:i:s',strtotime("+7 day"));
        $data['created_date'] = date('Y-m-d H:i:s');
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
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }
    /**
    * Update Announcement
    * @method PUT
    * @param integer $id ID of Announcement to update 
    * @param array $data Data Array as Follows:
    * @throws  Exception
    * <code>
    * {
    *  integer id,
    *  string name,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location,
    *  groups : [{'id' : integer}.....multiple]
    * }
    * </code>
    * @return array Returns the Created Announcement.
    */
    public function updateAnnouncement($id,&$data) {
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
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }
    /**
    * @ignore updateGroups
    */
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
    /**
    * @ignore deleteGroupsByAnnouncement
    */
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
    /**
    * @ignore getGroupsByAnnouncement
    */
    protected function getGroupsByAnnouncement($announcementId){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement_group_mapper')
        ->columns(array("group_id","announcement_id"))
        ->where(array('ox_announcement_group_mapper.announcement_id' => $announcementId));
        return $this->executeQuery($select)->toArray();
    }
    /**
    * @ignore insertAnnouncementForGroup
    */
    public function insertAnnouncementForGroup($announcementId, $groups){
        if($groups){
            $this->beginTransaction();
            try{
                $groupSingleArray= array_unique(array_map('current', $groups));
                $delete = $this->getSqlObject()
                ->delete('ox_announcement_group_mapper')
                ->where(['announcement_id' => $announcementId]);
                $result = $this->executeQueryString($delete);
                $query ="Insert into ox_announcement_group_mapper(announcement_id,group_id) Select $announcementId, id from ox_group where ox_group.id in (".implode(',', $groupSingleArray).")";
                $resultInsert = $this->runGenericQuery($query);
                if(count($resultInsert) == 0){
                    $this->rollback();
                    return 0;
                }
                $this->commit();
            }
            catch(Exception $e){
                $this->rollback();
                throw $e;
            }
             return 1; 
        }
        return 0;                    
    }
    /**
    * Delete Announcement
    * @param integer $id ID of Announcement to Delete
    * @return int 0=>Failure | $id;
    */
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
    /**
    * GET List Announcement
    * @method GET
    * @return array $dataget list of Announcements by User
    * <code></br>
    * {
    *  string name,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location,
    *  groups : [{'id' : integer}.....multiple]
    * }
    * </code>
    */
    public function getAnnouncements() {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("*"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array('group_id','announcement_id'),'left')
                ->join('ox_user_group', 'ox_announcement_group_mapper.group_id = ox_user_group.group_id',array('group_id','avatar_id'),'left')
                ->where(array('ox_user_group.avatar_id' => AuthContext::get(AuthConstants::USER_ID)))
                ->group(array('ox_announcement.id'));
        return $this->executeQuery($select)->toArray();
    }
    /**
    * GET Announcement
    * @param integer $id ID of the Announcement
    * @return array $dataget list of Announcements by User
    * <code></br>
    * {
    *  string name,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location,
    *  groups : [{'id' : integer}.....multiple]
    * }
    * </code>
    */
    public function getAnnouncement($id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement')
        ->columns(array("*"))
        ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array('group_id','announcement_id'),'left')
        ->join('ox_user_group', 'ox_announcement_group_mapper.group_id = ox_user_group.group_id',array('group_id','avatar_id'),'left')
        ->where(array('ox_announcement.id' => $id))
        ->group(array('ox_announcement.id'));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
            return 0;
        }
        return $response[0];
    }

    public function getAnnouncementsList(){
            $queryString = "select * from ox_announcement";
            $where = "where ox_announcement.org_id = ".AuthContext::get(AuthConstants::ORG_ID);
            $order = "order by ox_announcement.id";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
            return $resultSet->toArray();
    }
}
?>