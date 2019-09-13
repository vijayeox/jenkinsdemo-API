<?php
/**
* File Api
*/
namespace Oxzion\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Model\SubscriberTable;
use Oxzion\Model\Subscriber;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
/**
 * Comment Controller
 */
class SubscriberService extends AbstractService {
    /**
    * @var SubscriberService Instance of Subscriber Service
    */
    private $subscriberService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, SubscriberTable $table) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createSubscriber(&$data,$fileid) {
		$form = new Subscriber();
    //Additional fields that are needed for the create 
        $id = $data['user_id'];
        $query = "select id from ox_user";
        $order = "order by ox_user.id";
        $resultSet_User_temp = $this->executeQuerywithParams($query, null, null, $order)->toArray();
        $resultSet_User=array_map('current', $resultSet_User_temp);
		$data['file_id'] = $fileid;
		$data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
		$data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
		$data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
		$data['date_created'] = date('Y-m-d H:i:s');
		$data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
		$form->validate();
        if(!isset($data['user_id'])){
            return 0;
        }
        if(!in_array($id, $resultSet_User)) {
            return 0;
        }
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

	public function updateSubscriber ($id, &$data) {
		$obj = $this->table->get($id,array());
		if (is_null($obj)) {
			return 0;
		}
        $user_id = $data['user_id'];
        $query = "select id from ox_user";
        $order = "order by ox_user.id";
        $resultSet_User_temp = $this->executeQuerywithParams($query, null, null, $order)->toArray();
        $resultSet_User=array_map('current', $resultSet_User_temp);
        if(!in_array($user_id, $resultSet_User)) {
            return -1;
        }
		$form = new Subscriber();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
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

    public function deleteSubscriber($id) {
    	$obj = $this->table->get($id,array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Subscriber();
        $data = $obj->toArray();
        $data['id'] = $id;
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['isdeleted'] = 1;
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

    public function getSubscribers() {
    	$queryString = "select * from ox_subscriber";
    	$where = "where ox_subscriber.org_id=".AuthContext::get(AuthConstants::ORG_ID); 
    	$order = "order by ox_subscriber.id";
    	$resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
    	return $resultSet->toArray();
    }

    /*public function getchildren($id) {
    	$queryString = "select * from ox_comment";
    	$where = "where ox_comment.id =".$id." AND ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1";
    	$order = "order by ox_comment.id";
    	$resultSet = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
    	if($resultSet) {
    		$where = "where ox_comment.parent =".$id." AND ox_comment.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_comment.isdeleted!=1";
    		$result = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
    		if($result)
    			return $result;
    	}
    	return 0;
    }*/
}
?>