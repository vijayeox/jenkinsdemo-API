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
use Oxzion\ServiceException;




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
    public function createAnnouncement(&$data,$params = null){

        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions create announcement");
            }else{
                $data['org_id'] = $this->getIdFromUuid('ox_organization',$params['orgId']);    
            }
        }
        else{
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }

        try{
            $data['name'] = isset($data['name']) ? $data['name'] : NULL;
            $select = "SELECT uuid,name,status,end_date from ox_announcement where name = '".$data['name']."' and org_id = ".$data['org_id']." and end_date >= curdate()";
            $result = $this->executeQuerywithParams($select)->toArray();
            if(count($result) > 0){
                throw new ServiceException("Announcement already exists","announcement.exists");
            }   
       

            $form = new Announcement();
            $data['uuid'] = UuidUtil::uuid();
            $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
            $data['start_date'] = isset($data['start_date'])?$data['start_date']:date('Y-m-d');
            $data['status'] = $data['status']?$data['status']:1;
            $data['end_date'] = isset($data['end_date'])?$data['end_date']:date('Y-m-d',strtotime("+7 day"));
            $data['created_date'] = date('Y-m-d');
            $form->exchangeArray($data);
            $form->validate();
            $this->beginTransaction();
            $count = 0;
                $count = $this->table->save($form);
                if($count == 0){
                    $this->rollback();
                    throw new ServiceException("Failed to create","failed.announcement.create");
                }
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
                $this->commit();
            }catch(Exception $e){
                $this->rollback();
                throw $e;
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
    public function updateAnnouncement($uuid,&$data,$orgId = null) {
        if(isset($orgId)){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($orgId != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to update announcement");
            }else{
                $orgId = $this->getIdFromUuid('ox_organization',$orgId);
            }
        }

        $obj = $this->table->getByUuid($uuid,array());
        if(is_null($obj)){
            throw new ServiceException("Announcement not found","announcement.not.found");
        }

        $originalArray = $obj->toArray();
        if(isset($orgId)){
            if($orgId != $originalArray['org_id']){
                throw new ServiceException("Announcement does not belong to the organization","announcement.not.found");
            }
        }

        $form = new Announcement();
        $data = array_merge($originalArray, $data);
        $data['id'] = $originalArray['id'];
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        $groupsUpdated = 0;
        try{
            $count = $this->table->save($form); 
            $data['id'] = $originalArray['id'];
            if($count == 0){
                return 1;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            throw $e;
        }
        return $count;
    }
    /**
    * @ignore updateGroups
    */
    protected function updateGroups($announcementId,$groups){
        $oldGroups = array_column($this->getGroupsByAnnouncement($announcementId), 'group_id');
        $newGroups = array_column($groups,'id');
        $groupsRemoved = array_diff($oldGroups,$newGroups);  
        if(count($groupsRemoved) > 0){
            $result['delete'] = $this->deleteGroupsByAnnouncement($announcementId,$groupsRemoved);
            if($result['delete']!=count($groupsRemoved)||count($groupsRemoved)==0){
                return 0;
            }
        }
        $result['insert'] = $this->insertAnnouncementForGroup($announcementId,$groups);
        if($result['insert'] == 0){
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
    public function deleteAnnouncement($uuid,$params){

        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions delete announcement");
            }else{
                $params['orgId'] = $this->getIdFromUuid('ox_organization',$params['orgId']);    
            }
        }
        else{
            $params['orgId'] = AuthContext::get(AuthConstants::ORG_ID);
        }

        $obj = $this->table->getByUuid($uuid,array());

        $this->beginTransaction();
        $count = 0;
        try{
            $sql = $this->getSqlObject();
            $delete = $sql->delete('ox_announcement');
            $delete->where(['uuid' => $uuid,'org_id' => $params['orgId']]);
            $result = $this->executeUpdate($delete);
            
           
            if($result->getAffectedRows() == 0){
                $this->rollback();
                throw new ServiceException("Announcement not found","announcement.not.found");
            }

            $delete = "DELETE FROM ox_attachment where uuid = '".$obj->media."'";
            $this->executeQuerywithParams($delete);

            $select = "SELECT count(announcement_id) from `ox_announcement_group_mapper` where announcement_id = ".$obj->id;
            $count = $this->executeQuerywithParams($select)->toArray();

            if($count[0]['count(announcement_id)'] > 0){
                    $sql = $this->getSqlObject();
                    $delete = $sql->delete('ox_announcement_group_mapper');
                    $delete->where(['announcement_id' => $obj->id]);
                    $result = $this->executeUpdate($delete);
                    if($result->getAffectedRows() == 0){
                        $this->rollback();
                       throw new ServiceException("Failed to delete","failed.announcement.delete");
                    }
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
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
        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the announcement list");
            }else{
                $orgId = $this->getIdFromUuid('ox_organization',$params['orgId']);    
            }
        }else{
            $orgId = AuthContext::get(AuthConstants::ORG_ID); 
        }

        $select = "SELECT a.uuid,a.name,a.org_id,a.status,a.description,a.start_date,a.end_date,a.media_type,a.media from ox_announcement as a left join ox_announcement_group_mapper as ogm on a.id = ogm.announcement_id left join ox_user_group as oug on ogm.group_id = oug.group_id where oug.avatar_id = ".AuthContext::get(AuthConstants::USER_ID)." and a.org_id =".$orgId." and a.end_date >= curdate() union SELECT a.uuid,a.name,a.org_id,a.status,a.description,a.start_date,a.end_date,a.media_type,a.media from ox_announcement as a left join ox_announcement_group_mapper as ogm on a.id = ogm.announcement_id where ogm.group_id is NULL and a.org_id =".$orgId." and a.end_date >= curdate()";

        return $this->executeQuerywithParams($select)->toArray();
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
    public function getAnnouncement($id,$params)
    {
        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the announcement list");
            }else{
                $orgId = $this->getIdFromUuid('ox_organization',$params['orgId']);  
            }
        }else{
            $orgId = AuthContext::get(AuthConstants::ORG_ID); 
        }

       

        $select = "SELECT DISTINCT a.uuid,a.name,a.org_id,a.status,a.description,a.start_date,a.end_date,a.media_type,a.media from ox_announcement as a left join ox_announcement_group_mapper as ogm on a.id = ogm.announcement_id left join ox_user_group as oug on ogm.group_id=oug.group_id where a.org_id = ".$orgId." AND a.uuid = '".$id."' AND a.end_date >= curdate()";

        $response = $this->executeQuerywithParams($select)->toArray();
        if(count($response) == 0){
            return array();
        }
        return $response[0];
    }


    public function getAnnouncementsList($filterParams,$params)
    {

        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the announcement list");
            }else{
                $orgId = $this->getIdFromUuid('ox_organization',$params['orgId']);    
            }
        }else{
            $orgId = AuthContext::get(AuthConstants::ORG_ID); 
        }

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

            $where .= strlen($where) > 0 ? " AND org_id =".$orgId." AND end_date >= curdate()" : " WHERE org_id =".$orgId." AND end_date >= curdate()";
            
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(id)'];
            $query ="SELECT uuid, name, org_id, status, description, start_date, end_date, media_type, media FROM `ox_announcement`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query)->toArray();
            return array('data' => $resultSet,
                     'total' => $count);
    }


    public function getAnnouncementGroupList($params,$filterParams = null) {

        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the group list of announcement");
            }else{
                $orgId = $this->getIdFromUuid('ox_organization',$params['orgId']);    
            }
        }else{
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
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



            $where .= strlen($where) > 0 ? " AND ox_announcement.uuid = '".$params['announcementId']."' AND ox_announcement.end_date >= now() AND ox_group.status = 1 AND ox_announcement.org_id = ".$orgId : " WHERE ox_announcement.uuid = '".$params['announcementId']."' AND ox_announcement.end_date >= curdate() AND ox_group.status = 1 AND ox_announcement.org_id = ".$orgId;

            
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(ox_group.id)'];
            $query =$query." ".$from." ".$where." ".$sort." ".$limit;

            $resultSet = $this->executeQuerywithParams($query);
            return array('data' => $resultSet->toArray(), 
                     'total' => $count);
    
    }


    public function saveGroup($params,$data){
        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to add groups to announcement");
            }else{
               $params['orgId'] = $this->getIdFromUuid('ox_organization',$params['orgId']);
            }
        }
        else{
           $params['orgId'] = AuthContext::get(AuthConstants::ORG_ID);
        }

        $obj = $this->table->getByUuid($params['announcementId'],array());
        if (is_null($obj)) {
            throw new ServiceException("Announcement not found","announcement.not.found");
        }

        $org = $this->organizationService->getOrganization($obj->org_id);

       
        if(isset($params['orgId'])){
            if($params['orgId'] != $obj->org_id){
        }
        if (!isset($data['groups']) || empty($data['groups'])) {
            return 2;

        if(!isset($data['groups']) || empty($data['groups'])) {
             throw new ServiceException("Enter Group Ids","select.group");
        }

        $announcementId = $obj->id;
        $orgId = $params['orgId'];
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
           throw new ServiceException("Entity not found","Announcemnet.not.found");
    }
 }
}
}