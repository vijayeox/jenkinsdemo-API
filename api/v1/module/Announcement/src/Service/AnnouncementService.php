<?php
namespace Announcement\Service;

use Oxzion\Service\AbstractService;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\FilterUtils;
use Oxzion\Service\OrganizationService;
use Oxzion\AccessDeniedException;
use Oxzion\Security\SecurityManager;



/**
 * Announcement Service
 */
class AnnouncementService extends AbstractService
{
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
    public function __construct($config, $dbAdapter, AnnouncementTable $table, OrganizationService $organizationService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->organizationService = $organizationService;
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
    public function createAnnouncement(&$data)
    {
        $form = new Announcement();
        $data['uuid'] = UuidUtil::uuid();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['start_date'] = isset($data['start_date'])?$data['start_date']:date('Y-m-d');
        $data['status'] = $data['status']?$data['status']:1;
        $data['end_date'] = isset($data['end_date'])?$data['end_date']:date('Y-m-d', strtotime("+7 day"));
        $data['created_date'] = date('Y-m-d');
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
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
    public function updateAnnouncement($uuid, &$data)
    {
        $id = $this->getAnnouncementIdBYUuid($uuid);
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new Announcement();
        $data = array_merge($originalArray, $data);
        $data['id'] = $id;
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        $groupsUpdated = 0;
        try {
            $count = $this->table->save($form);
            $data['id'] = $id;
            if ($count == 0) {
                $this->rollback();
                return 1;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }
    /**
    * @ignore updateGroups
    */
    protected function updateGroups($announcementId, $groups)
    {
        $oldGroups = array_column($this->getGroupsByAnnouncement($announcementId), 'group_id');
        $newGroups = array_column($groups, 'id');
        $groupsRemoved = array_diff($oldGroups, $newGroups);
        if (count($groupsRemoved) > 0) {
            $result['delete'] = $this->deleteGroupsByAnnouncement($announcementId, $groupsRemoved);
            if ($result['delete']!=count($groupsRemoved)||count($groupsRemoved)==0) {
                return 0;
            }
        }
        $result['insert'] = $this->insertAnnouncementForGroup($announcementId, $groups);
        if ($result['insert'] == 0) {
            return 0;
        }
        return 1;
    }
    /**
    * @ignore deleteGroupsByAnnouncement
    */
    protected function deleteGroupsByAnnouncement($announcementId, $groupIdList)
    {
        $rowsAffected = 0;
        foreach ($groupIdList as $key => $groupId) {
            $sql = $this->getSqlObject();
            $delete = $sql->delete('ox_announcement_group_mapper');
            $delete->where(['announcement_id' => $announcementId,'group_id' => $groupId]);
            $result = $this->executeUpdate($delete);
            if ($result->getAffectedRows() == 0) {
                break;
            }
            $rowsAffected++;
        }
        return $rowsAffected;
    }
    /**
    * @ignore getGroupsByAnnouncement
    */
    protected function getGroupsByAnnouncement($announcementId)
    {
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
    public function insertAnnouncementForGroup($announcementId, $groups)
    {
        if ($groups) {
            $this->beginTransaction();
            try {
                $groupSingleArray= array_unique(array_map('current', $groups));
                $delete = $this->getSqlObject()
                ->delete('ox_announcement_group_mapper')
                ->where(['announcement_id' => $announcementId]);
                $result = $this->executeQueryString($delete);

                $query ="Insert into ox_announcement_group_mapper(announcement_id,group_id) Select $announcementId, id from ox_group where ox_group.uuid in (".implode(',', $groupSingleArray).")";
                $resultInsert = $this->runGenericQuery($query);
                if (count($resultInsert) == 0) {
                    $this->rollback();
                    return 0;
                }
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
            return 1;
        }
        return 0;
    }


    protected function getAnnouncementIdBYUuid($uuid)
    {
        $select = "SELECT id from `ox_announcement` where uuid = '".$uuid."'";
        $id = $this->executeQuerywithParams($select)->toArray();
        if (count($id) > 0) {
            return $id[0]['id'];
        }
        return 0;
    }
    /**
    * Delete Announcement
    * @param integer $id ID of Announcement to Delete
    * @return int 0=>Failure | $id;
    */
    public function deleteAnnouncement($uuid)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $id = $this->getAnnouncementIdBYUuid($uuid);


            $sql = $this->getSqlObject();
            $delete = $sql->delete('ox_announcement');
            $delete->where(['uuid' => $uuid,'org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
            $result = $this->executeUpdate($delete);
            
           
            if ($result->getAffectedRows() == 0) {
                $this->rollback();
                return 0;
            }

            $select = "SELECT count(announcement_id) from `ox_announcement_group_mapper` where announcement_id = ".$id;
            $count = $this->executeQuerywithParams($select)->toArray();

            if ($count[0]['count(announcement_id)'] > 0) {
                $sql = $this->getSqlObject();
                $delete = $sql->delete('ox_announcement_group_mapper');
                $delete->where(['announcement_id' => $id]);
                $result = $this->executeUpdate($delete);
                if ($result->getAffectedRows() == 0) {
                    $this->rollback();
                    return 0;
                }
            }
            $this->commit();
        } catch (Exception $e) {
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
    public function getAnnouncements()
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement')
                ->columns(array("uuid", "name", "org_id", "status", "description", "start_date", "end_date", "media_type", "media"))
                ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array('group_id','announcement_id'), 'left')
                ->join('ox_user_group', 'ox_announcement_group_mapper.group_id = ox_user_group.group_id', array('group_id','avatar_id'), 'left')
                ->where(array('ox_user_group.avatar_id' => AuthContext::get(AuthConstants::USER_ID)));
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
    public function getAnnouncement($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_announcement')
        ->columns(array("uuid", "name", "org_id", "status", "description", "start_date", "end_date", "media_type", "media"))
        ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array('group_id','announcement_id'), 'left')
        ->join('ox_user_group', 'ox_announcement_group_mapper.group_id = ox_user_group.group_id', array('group_id','avatar_id'), 'left')
        ->where(array('ox_announcement.uuid' => $id));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }

    public function getAnnouncementsList($filterParams)
    {
        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "name";

        $cntQuery ="SELECT count(id) FROM `ox_announcement`";

        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
                $filterArray = json_decode($filterParams['filter'],true); 
                if(isset($filterArray[0]['filter'])){
                   $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                   $filterList = $filterArray[0]['filter']['filters'];
                   $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort);
                }
                
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];            
            }

            $where .= strlen($where) > 0 ? " AND org_id =".AuthContext::get(AuthConstants::ORG_ID) : " WHERE org_id =".AuthContext::get(AuthConstants::ORG_ID);
            
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(id)'];
            $query ="SELECT uuid, name, org_id, status, description, start_date, end_date, media_type, media FROM `ox_announcement`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query)->toArray();
            return array('data' => $resultSet, 
                     'total' => $count);
    }


    public function getAnnouncementGroupList($id, $filterParams = null)
    {
        if (!isset($id)) {
            return 0;
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "ox_group.name";


         $query = "SELECT ox_group.uuid,ox_group.name";
         $from = " FROM ox_group left join ox_announcement_group_mapper on ox_group.id = ox_announcement_group_mapper.group_id left join ox_announcement on ox_announcement.id = ox_announcement_group_mapper.announcement_id";
    
         $cntQuery ="SELECT count(ox_group.id)".$from;

         if(count($filterParams) > 0 || sizeof($filterParams) > 0){
                $filterArray = json_decode($filterParams['filter'],true); 
                if(isset($filterArray[0]['filter'])){
                   $filterlogic = $filterArray[0]['filter']['logic'];
                   $filterList = $filterArray[0]['filter']['filters'];
                   $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort);
                }
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];            
            }



            $where .= strlen($where) > 0 ? " AND ox_announcement.uuid = '".$id."' AND ox_announcement.end_date >= now() AND ox_group.status = 1" : " WHERE ox_announcement.uuid = '".$id."' AND ox_announcement.end_date >= curdate() AND ox_group.status = 1";

            
        $sort = " ORDER BY ".$sort;
        $limit = " LIMIT ".$pageSize." offset ".$offset;
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count(ox_group.id)'];
        $query =$query." ".$from." ".$where." ".$sort." ".$limit;

        $resultSet = $this->executeQuerywithParams($query);
        return array('data' => $resultSet->toArray(),
                     'total' => $count);
    }


    public function saveGroup($id,$data){
        if(isset($data['org_id'])){
            $data['org_id'] = $this->organizationService->getOrganizationIdByUuid($data['org_id']);
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_ID))) {
                throw new AccessDeniedException("You do not have permissions to add users to project");
            }
        }
        else{
           $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID); 
        }

        $obj = $this->table->getByUuid($id,array());
        if (is_null($obj)) {
            return 0;
        }
        if(!isset($data['groups']) || empty($data['groups'])) {
            return 2;
        }
        $announcementId = $obj->id;
        $orgId = $data['org_id'];
        
    
        if($data['groups']){
            $groupSingleArray= array_map('current', $data['groups']);
            try{
               
                $delete = "DELETE oag FROM ox_announcement_group_mapper as oag
                            inner join ox_group as og on oag.group_id = og.id where og.uuid not in ('".implode("','", $groupSingleArray)."') and oag.announcement_id = ".$announcementId." and og.org_id =".$orgId." and og.status = 'Active'";

                $result = $this->executeQuerywithParams($delete);
             
                $query ="Insert into ox_announcement_group_mapper(announcement_id,group_id) SELECT ".$announcementId.",og.id from ox_group as og LEFT OUTER JOIN ox_announcement_group_mapper as oag on og.id = oag.group_id and oag.announcement_id = ".$announcementId." where og.uuid in ('".implode("','", $groupSingleArray)."') and og.org_id = ".$orgId." and og.status = 'Active' and oag.announcement_id is null";

                $resultInsert = $this->runGenericQuery($query);
            }
            catch(Exception $e){
                throw $e;
            }
            return 1;
        }
        return 0;
    }
}
?>