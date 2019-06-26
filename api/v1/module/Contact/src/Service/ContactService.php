<?php
namespace Contact\Service;

use Oxzion\Service\AbstractService;
use Contact\Model\ContactTable;
use Contact\Model\Contact;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
use Ramsey\Uuid\Uuid;
use Oxzion\Utils\FileUtils;
use Oxzion\Service\UserService;


class ContactService extends AbstractService
{

    private $table;
    public const ALL_FIELDS = "-1";

    public function __construct($config, $dbAdapter, ContactTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    /**
     * @param $data
     * @return int|string
     *
     */
    public function createContact(&$data,$files = NULL)
    {
        $form = new Contact();

        if(isset($data['uuid'])){
            $data['user_id'] = $this->getUserByUuid($data['uuid']);
        }
        else{
            $data['user_id'] = NULL;
        }
        unset($data['uuid']);
        $data['uuid'] = Uuid::uuid4();
        $data['user_id'] = (isset($data['user_id'])) ? $data['user_id'] : null;
        $data['icon_type'] = (isset($data['icon_type'])) ? $data['icon_type'] : TRUE; 
        $data['owner_id'] = (isset($data['owner_id'])) ? $data['owner_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['created_id'] = (isset($data['created_id'])) ? $data['created_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);

            if(isset($files)){
                print("FIE");
                $this->uploadContactIcon($data['uuid'],$data['owner_id'],$files);
            }
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

    public function updateContact($id,&$data,$files)
    {
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            return 2;
        }
        $form = new Contact();
        $data = array_merge($obj->toArray(), $data);
        $data['owner_id'] = ($data['owner_id']) ? $data['owner_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if(isset($files)){
                $this->uploadContactIcon($data['uuid'],$data['owner_id'],$files);
            }
            if ($count == 0) {
                $this->rollback();
                return 1;
            }
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }


    public function getContactsByUuid($uuid){
        $select = "SELECT * from `ox_contact` where uuid = '".$uuid."'";
        $result = $this->executeQuerywithParams($select)->toArray();
        if($result == 0){
            return 0;
        }
        return $result;
    }

    public function deleteContact($id)
    {
        $sql = $this->getSqlObject();
        $count = 0;
        try {
            $delete = $sql->delete('ox_contact');
            $delete->where(['uuid' => $id]);
            $result = $this->executeUpdate($delete);
            if($result->getAffectedRows() == 0)
                {
                    $this->rollback();
                    return 0;
                }
            else {
                    return 1;
                 }
        } catch (Exception $e) {
            $this->rollback();
        }
        return $count;
    }

    public function getContacts($column = ContactService::ALL_FIELDS, $filter=null){
        // filter criteria, column control - all or name
        // filter for searching
        // print_r('col: '.gettype($column));
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $orgId = AuthContext::get(AuthConstants::ORG_ID);

        $queryString1 = "SELECT * from (";

        if($column == ContactService::ALL_FIELDS){
            $queryString2 = "SELECT oxc.uuid as uuid, user_id, oxc.first_name, oxc.last_name, oxc.phone_1, oxc.phone_list, oxc.email, oxc.email_list, oxc.company_name, oxc.icon_type,oxc.designation, oxc.country, '1' as contact_type from ox_contact as oxc";
        } else {
            $queryString2 = "SELECT oxc.uuid as uuid,user_id, oxc.first_name, oxc.last_name, oxc.icon_type, '1' as contact_type  from ox_contact as oxc";
        }
        $where1 = " WHERE oxc.owner_id = " . $userId . " ";

        if($filter == null){
            $and1  = '';
        } else {
            $and1 = " AND (LOWER(oxc.first_name) like '%".$filter."%' OR LOWER(oxc.last_name) like '%".$filter."%' OR LOWER(oxc.email) like '%".$filter."%' OR lower(oxc.phone_1) like '%".$filter."%')";
        }

        $union = " UNION ";

        if($column == "-1"){
            $queryString3 = "SELECT ou.uuid as uuid, ou.id as user_id, ou.firstname as first_name, ou.lastname as last_name, ou.phone as phone_1, null as phone_list, ou.email, null as email_list, org.name as company_name, null as icon_type,ou.designation,ou.country, '2' as contact_type  from ox_user as ou inner join ox_organization as org on ou.orgid = org.id";
        } else {
            $queryString3 = "SELECT ou.uuid as uuid, ou.id as user_id, ou.firstname as first_name, ou.lastname as last_name,null as icon_type, '2' as contact_type  from ox_user as ou";
        }

        $where2 = " WHERE ou.orgid = " . $orgId . "";

        if($filter == null){
            $and2 = '';
        } else {
            $and2 = " AND (LOWER(ou.firstname) like '%".$filter."%' OR LOWER(ou.lastname) like '%".$filter."%' OR LOWER(ou.email) like '%".$filter."%')";
        }

        $queryString4 = ") as a ORDER BY a.first_name, a.last_name";

        $finalQueryString = $queryString1.$queryString2.$where1.$and1.$union.$queryString3.$where2.$and2.$queryString4;
        $resultSet = $this->executeQuerywithParams($finalQueryString);
        $resultSet = $resultSet->toArray();
        $myContacts = array();
        $orgContacts = array();
        foreach ($resultSet as $key => $row) {
            if($row['contact_type'] == 1){
                array_push($myContacts, $row);
            } else {
                array_push($orgContacts, $row);
            }

        }

        return $resultSet1 = ['myContacts' => $myContacts, 'orgContacts' => $orgContacts];

    }



    public function getContactIconPath($ownerId,$ensureDir=false){
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder."contact/".$ownerId;
        if(isset($ownerId)){
            $folder = $folder."/";
        }

        
        if($ensureDir && !file_exists($folder)){
            FileUtils::createDirectory($folder);
        }

        return $folder;
    }

    public function getUuidById($userId){
        $select = "SELECT uuid from ox_user where id = ".$userId." AND orgid = ".AuthContext::get(AuthConstants::ORG_ID);
        $result = $this->executeQuerywithParams($select)->toArray();
        if($result){
            return $result[0]['uuid'];   
        }
    }

    public function getUserByUuid($uuid){
        $select = "SELECT id from `ox_user` where uuid = '".$uuid."'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if($result){
        return $result[0]['id'];
    }
    }


    /**
     * createUpload
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
    */
    public function uploadContactIcon($uuid,$owner_id,$file){
        
        $id = $this->getUuidById($owner_id);

        if(isset($file)){

           $destFile = $this->getContactIconPath($id,true);
           $image = FileUtils::convetImageTypetoPNG($file);
           if($image){
                if(FileUtils::fileExists($destFile)){
                    imagepng($image, $destFile.'/'.$uuid.'.png');
                    $image = null;
                }
                else {
                    mkdir($destFile);
                    imagepng($image, $destFile.'/'.$uuid.'.png');
                    $image = null;
                }
            }     
        }
    }

}
