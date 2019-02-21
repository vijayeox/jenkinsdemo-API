<?php
namespace Email\Service;

use Bos\Service\AbstractService;
use Email\Model\EmailTable;
use Email\Model\Email;
use Oxzion\Encryption\TwoWayEncryption;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class EmailService extends AbstractService {

	private $table;

	public function __construct($config, $dbAdapter, EmailTable $table) {
		parent::__construct($config, $dbAdapter);
		$this->table = $table;
	}

	public function createEmailAccount(&$data) {
		$form = new Email();
        if($data['password'])
            $data['password'] = TwoWayEncryption::encrypt($data['password']);
        $data['userid'] = AuthContext::get(AuthConstants::USER_ID);
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

	public function updateEmailAccount ($id, &$data) {
		$obj = $this->table->get($id,array());
		if (is_null($obj)) {
			return 0;
		}
		$form = new Email();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
        $data['userid'] = AuthContext::get(AuthConstants::USER_ID);
        if($data['password'])
            $data['password'] = TwoWayEncryption::encrypt($data['password']);
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

    public function deleteEmail($id) {
    	$count = 0;
        try{
            $count = $this->table->delete($id);
            if($count == 0){
                return 0;
            }
        }catch(Exception $e){
            return 0;
        }
        return $count;
    }

    public function getEmailAccountsByUserId() {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select id,userid,email,username,host,isdefault from email_setting_user";
        $where = "where email_setting_user.userid = " . $userId; 
        $order = "order by email_setting_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function getEmailAccountByUserId($id) {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select id,userid,email,username,host,isdefault from email_setting_user";
        $where = "where email_setting_user.userid = " . $userId." AND email_setting_user.id =".$id; 
        $order = "order by email_setting_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }
    
}