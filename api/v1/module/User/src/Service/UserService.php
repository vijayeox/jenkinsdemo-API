<?php
namespace User\Service;

use Oxzion\Service\AbstractService;
use User\Model\UserTable;
use User\Model\User;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\FileService;
use Oxzion\Auth\AuthConstants;
use Oxzion\File\FileConstants;
use Oxzion\ValidationException;
use Exception;

class UserService extends AbstractService{
    const USER_FOLDER = "/users/";

    private $table;

    public function __construct($config, $dbAdapter, UserTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    protected function getUserFolder($id){
        return $this->config['DATA_FOLDER']."organization/".AuthContext::get(AuthConstants::ORG_ID).self::USER_FOLDER.$id;
    }
    public function getFileName($file){
        $fileName = explode('-', $file,2);
        return $fileName[1];
    }

    public function createUser(&$data){
        $form = new User();
        $data['orgid'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['avatar_date_created'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();

        $count = 0;
            $count = $this->table->save($form);
            if($count == 0){
                return 0;
            }
            if($this->getErrorCode != 0){
                if($this->getErrorCode == 1)
                $this->getFailureResponse("User already exists", 404, $data);
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            // @@TODO $this->sendEmail();
        return $count;
    }
  
    public function updateUser($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new User();
        $data = array_merge($originalArray, $data);
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
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }

   

    public function deleteUser($id){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new User();
        $originalArray['status'] = 'Inactive';
        $form->exchangeArray($originalArray);
        $form->validate();
        $result = $this->table->save($form);
        return $result;
    }

    public function getUsers($group_id=NULL) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('avatars')
         ->columns(array("*"))
         ->where(array('avatars.orgid' => AuthContext::get(AuthConstants::ORG_ID)));
        if($group_id){
            $select->join('groups_avatars', 'avatars.id = groups_avatars.avatarid',array('groupid','avatarid'),'left')
            ->where(array('groups_avatars.groupid' => $group_id));
        }
        return $this->executeQuery($select)->toArray();
    }
    public function getUser($id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('avatars')
         ->columns(array("*"))
         ->where(array('avatars.orgid' => AuthContext::get(AuthConstants::ORG_ID),'avatars.id' => $id));
        $response = $this->executeQuery($select)->toArray();
        if(isset($response[0])){
            return $response[0];
        } else {
            return 0;
        }
    }
}
?>
