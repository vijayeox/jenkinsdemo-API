<?php
namespace Group\Service;

use Oxzion\Service\AbstractService;
use Group\Model\GroupTable;
use Group\Model\Group;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class GroupService extends AbstractService {

    private $table;

    public function __construct($config, $dbAdapter, GroupTable $table) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function getGroupsforUser($userId) {
    $queryString = "Select usr_grp.id, usr_grp.avatar_id, usr_grp.group_id, grp.name, grp.manager_id, grp.parent_id from ox_user_group as usr_grp 
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
}