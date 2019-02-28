<?php
namespace Group\Service;

use Bos\Service\AbstractService;
use Group\Model\GroupTable;
use Group\Model\Group;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class GroupService extends AbstractService {

    private $table;

    public function __construct($config, $dbAdapter, GroupTable $table) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function getGroupsforUser($userId) {
    $queryString = "select usr_grp.id, usr_grp.avatar_id, usr_grp.group_id, grp.name, grp.manager_id, grp.parent_id from ox_user_group as usr_grp 
        left join ox_group as grp on usr_grp.group_id = grp.id";
    $where = "where avatar_id = " . $userId;
    $order = "order by grp.name";
    $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
    return $resultSet->toArray();
    }

    public function createGroup(&$data) {
        $form = new Group();   
        $data['name'] = $data['name'] ? $data['name'] : uniqid();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function updateGroup ($id, &$data) {
        $obj = $this->table->get($id,array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Group();
        $data = array_merge($obj->toArray(), $data);
        $data['id'] = $id;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function deleteGroup($id) {
        $count = 0;
        try {
            $count = $this->table->delete($id);
            if($count == 0) {
                return 0;
            }
        } catch(Exception $e) {
            $this->rollback();
        }
        return $count;
    }

    public function getuserlist($id) {
        $queryString = "select id from ox_group";
        $order = "order by ox_group.id";
        $resultSet_temp = $this->executeQuerywithParams($queryString, null, null, $order)->toArray();
        $resultSet=array_map('current', $resultSet_temp);
        if(in_array($id, $resultSet)) {
            $query = "select avatar_id from ox_user_group";
            $where = "where group_id =".$id;
            $order = "order by ox_user_group.group_id";
            $resultSet_User = $this->executeQuerywithParams($query, $where, null, $order)->toArray();
            return $resultSet_User;
        }
        else {
            return 0;
        }
    }

    public function saveUser($id,$data) {
        $avatar_id = array();
        $userArray=json_decode($data['userid'],true);

        $query = "select id from ox_user";
        $order = "order by ox_user.id";
        $resultSet_User_temp = $this->executeQuerywithParams($query, null, null, $order)->toArray();
        $resultSet_User=array_map('current', $resultSet_User_temp);

        if($userArray){
            $storeData = array();
            $queryString = "select avatar_id from ox_user_group";
            $id_group_array = $this->executeQuerywithParams($queryString)->toArray();
            if($id_group_array) {
                $userSingleArray = array_map('current', $userArray);
                $avatar_id = array_column($id_group_array, 'avatar_id');
                if((count(array_diff($userSingleArray, $avatar_id))!=0)&&(count(array_intersect($userSingleArray, $resultSet_User))==count($userSingleArray))) {
                    $sql = $this->getSqlObject();
                    $delete = $sql->delete('ox_user_group');
                    $result = $this->executeUpdate($delete);
                    foreach ($userArray as $key => $value) {
                        $storeData[] = array('group_id'=>$id,'avatar_id'=>$value['id']);
                    }
                    $queryString =$this->multiInsertOrUpdate('ox_user_group',$storeData,array());
                }
                else {
                    return -1;
                }
            }
            else {
                foreach ($userArray as $key => $value) {
                    $storeData[] = array('group_id'=>$id,'avatar_id'=>$value['id']);
                }
                $queryString =$this->multiInsertOrUpdate('ox_user_group',$storeData,array());
            }
        }
        else{
            return 0;
        }
        return 1;
    }
}