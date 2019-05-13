<?php
namespace SplashPage\Service;

use Bos\Service\AbstractService;
use SplashPage\Model\SplashPageTable;
use SplashPage\Model\SplashPage;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
//use Oxzion\ValidationException; does this even exist?
use Zend\Db\Sql\Expression;
use Bos\ValidationException;
use Exception;
/**
 * SplashPage Service
 */
class SplashPageService extends AbstractService{
    /**
    * @ignore SPLASHPAGE_FOLDER
    */
    const SPLASHPAGE_FOLDER = "/splashpage/";
    /**
    * @ignore table
    */
    private $table;
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, SplashPageTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    /**
    * Create SplashPage
    * @param array $data Array of elements as shown</br>
    * <code> 
    *        org_id : integer,
    *        content : string,
    *        enable:  integer
    * </code>
    * @return integer 0|$id of SplashPage Created
    */
    public function createSplashPage(&$data){
        $form = new SplashPage();
        // if(isset($data['groups'])){
        //     $groups = json_decode($data['groups'],true);
        //     unset($data['groups']);
        // }
        
        $form->exchangeArray($data);
        $form->validate();

        if ($this->PageAlreadyExists($data['org_id'])){ //don't create a new page if one already there
            $validationException = new ValidationException();
            $errors = ['Already_Exists' => "There already exists a page with an org_id of " . $data['org_id'] . "."];
            $validationException->setErrors($errors);
            throw $validationException;
        }

        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
        // $id = $this->table->getLastInsertValue();
        //     $data['id'] = $id;
        //     if(isset($groups)){
        //         $affected = $this->insertAnnouncementForGroup($id,$groups);
        //         if(is_string($groups) && $affected != count($groups)) {
        //             $this->rollback();
        //             return 0;
        //         }
        //     }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }

    /**
     * Determine if this page exists.  Needed when creating a page to verifiy the page is not already created
     * @param int org_id
     * @return int The number of splashpages found for this org_id (will be 1 or 0) 
     */
    private function PageAlreadyExists($org_id){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_splashpage')
                ->columns(array("id"))
                ->where(array('ox_splashpage.org_id' => $org_id));
        $result = $this->executeQuery($select)->toArray();
        return count($result);
    }
    
    /**
     * Find the id for a given page.  Needed in order to update a splashpage. 
     * @param int org_id  
     * @return int the splashpage id for the given org_id 
     */
    public function GetSplashpageId($org_id){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_splashpage')
                ->columns(array("id"))
                ->where(array('ox_splashpage.org_id' => $org_id));
        $result = $this->executeQuery($select)->toArray();
        if (count($result) == 0){
            $validationException = new ValidationException();
            $errors = ['No_Splashpage' => "There is no splash page for org_id of " . $org_id . "."];
            $validationException->setErrors($errors);
            throw $validationException;
        }
        return $result[0]["id"];         
    }

    /** 
    * Get the page for this user
    * @return array the Splashpage for the organization Id of this User
    */
    public function getSplashPages() {
        $org_id = AuthContext::get(AuthConstants::ORG_ID);
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_splashpage')
                ->columns(array("*"))
                ->where(array('ox_splashpage.org_id' => $org_id));
        $result = $this->executeQuery($select)->toArray();
        if (count($result) == 0){
            $validationException = new ValidationException();
            $errors = ['No_Splashpage' => "There is no splash page for org_id of " . $org_id . "."];
            $validationException->setErrors($errors);
            throw $validationException;
        }
        return $result;
    }

    /**
    * Get the page by organization
    * @param int orgranizaionId 
    * @return array the Splashpage for the organization Id
    */
    public function getSplashpageforOrganizaion($organizationId) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_splashpage')
                ->columns(array("*"))
                ->where(array('ox_splashpage.org_id' => $organizationId));
        $result = $this->executeQuery($select)->toArray();
        if (count($result) == 0){
            $validationException = new ValidationException();
            $errors = ['No_Splashpage' => "There is no splash page for org_id of " . $organizationId . "."];
            $validationException->setErrors($errors);
            throw $validationException;
        }
        return $this->executeQuery($select)->toArray();
            
    }

    /**
    * Delete SplashPage
    * @param integer $id ID of SplashPage to Delete
    * @return int 0=>Failure | $id;
    */
    public function deleteSplashPage($id){
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
    * @ignore updateGroups
    */
    protected function updateGroups($announcementId,$groups){
        // $oldGroups = array_column($this->getGroupsByAnnouncement($announcementId), 'group_id');
        // $newGroups = array_column($groups,'id');
        // $groupsAdded = array_diff($newGroups,$oldGroups);
        // $groupsRemoved = array_diff($oldGroups,$newGroups);
        // $insertGroups = array();
        // foreach ($groupsAdded as $key => $value) {
        //     $insertGroups[$key]['id'] = $value;
        // }
        // $result['insert'] = $this->insertAnnouncementForGroup($announcementId,$insertGroups);
        // if($result['insert']!=count($groupsAdded)){
        //     return 0;
        // }
        // $result['delete'] = $this->deleteGroupsByAnnouncement($announcementId,$groupsRemoved);
        // if($result['delete']!=count($groupsRemoved)||count($groupsRemoved)==0){
        //     return 0;
        // }
        // return 1;
    }
    /**
    * @ignore deleteGroupsBySplashPage
    */
    protected function deleteGroupsBySplashPage($announcementId,$groupIdList){
        // $rowsAffected = 0;
        // foreach ($groupIdList as $key => $groupId) {
        //     $sql = $this->getSqlObject();
        //     $delete = $sql->delete('ox_announcement_group_mapper');
        //     $delete->where(['announcement_id' => $announcementId,'group_id' => $groupId]);
        //     $result = $this->executeUpdate($delete);
        //     if($result->getAffectedRows() == 0){
        //         break;
        //     }
        //     $rowsAffected++; 
        // }
        // return $rowsAffected;
    }
    /**
    * @ignore getGroupsBySplashPage
    */
    protected function getGroupsBySplashPage($announcementId){
        // $sql = $this->getSqlObject();
        // $select = $sql->select();
        // $select->from('ox_announcement_group_mapper')
        // ->columns(array("group_id","announcement_id"))
        // ->where(array('ox_announcement_group_mapper.announcement_id' => $announcementId));
        // return $this->executeQuery($select)->toArray();
    }
    /**
    * @ignore insertSplashPageForGroup
    */
    public function insertSplashPageForGroup($announcementId, $groups){
    //     if($groups){
    //         $this->beginTransaction();
    //         try{
    //             $groupSingleArray= array_unique(array_map('current', $groups));
    //             $delete = $this->getSqlObject()
    //             ->delete('ox_announcement_group_mapper')
    //             ->where(['announcement_id' => $announcementId]);
    //             $result = $this->executeQueryString($delete);
    //             $query ="Insert into ox_announcement_group_mapper(announcement_id,group_id) Select $announcementId, id from ox_group where ox_group.id in (".implode(',', $groupSingleArray).")";
    //             $resultInsert = $this->runGenericQuery($query);
    //             if(count($resultInsert) == 0){
    //                 $this->rollback();
    //                 return 0;
    //             }
    //             $this->commit();
    //         }
    //         catch(Exception $e){
    //             $this->rollback();
    //             throw $e;
    //         }
    //          return 1; 
    //     }
    //     return 0;                    
    }

    /**
    * GET SplashPage
    * @param integer $id ID of the SplashPage
    * @return array $dataget list of SplashPages by User
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
    public function getSplashPage($id) {
        // $sql = $this->getSqlObject();
        // $select = $sql->select();
        // $select->from('ox_announcement')
        // ->columns(array("*"))
        // ->join('ox_announcement_group_mapper', 'ox_announcement.id = ox_announcement_group_mapper.announcement_id', array('group_id','announcement_id'),'left')
        // ->join('ox_user_group', 'ox_announcement_group_mapper.group_id = ox_user_group.group_id',array('group_id','avatar_id'),'left')
        // ->where(array('ox_announcement.id' => $id))
        // ->group(array('ox_announcement.id'));
        // $response = $this->executeQuery($select)->toArray();
        // if(count($response)==0){
        //     return 0;
        // }
        // return $response[0];
    }

    public function getSplashPagesList(){
            // $queryString = "select * from ox_announcement";
            // $where = "where ox_announcement.org_id = ".AuthContext::get(AuthConstants::ORG_ID);
            // $order = "order by ox_announcement.id";
            // $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
            // return $resultSet->toArray();
    }
}

/**
 * 
 * Update SplashPage (not used since the id is not passed through the url instead see replaceList() in the SplashPageController class)
    * @method PUT
    * @param integer $id ID of SplashPage to update 
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
    // public function updateSplashPage($id,&$data) {
    //     $obj = $this->table->get($id,array());
    //     if(is_null($obj)){
    //         return 0;
    //     }
    //     $originalArray = $obj->toArray();
    //     $form = new SplashPage();
    //     $data = array_merge($originalArray, $data);
    //     $data['id'] = $id;
    //     if(isset($data['groups'])){
    //         $groups = json_decode($data['groups'],true);
    //         unset($data['groups']);
    //     }
    //     $form->exchangeArray($data);
    //     $form->validate();
    //     $this->beginTransaction();
    //     $count = 0;
    //     echo "The form dump is ";
    //     var_dump($form);
    //     try{
    //         $count = $this->table->save($form);
    //         if($count == 0){
    //             $this->rollback();
    //             return 0;
    //         }
    //         $data['id'] = $id;
    //         if(isset($data['groups'])){
    //             $groupsUpdated = $this->updateGroups($id,$groups);
    //             if(!$groupsUpdated) {
    //                 $this->rollback();
    //                 return 0;
    //             }
    //         } else {
    //             //TODO handle this case properly
    //         }
    //         $this->commit();
    //     }catch(Exception $e){
    //         $this->rollback();
    //         return 0;
    //     }
    //     echo "Hello from updateSplashPage the count to return is $count";
    //     return $count;
    // }


?>