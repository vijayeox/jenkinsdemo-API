<?php
namespace Email\Service;

use Bos\Auth\AuthConstants;
use Bos\Auth\AuthContext;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Email\Model\Email;
use Email\Model\EmailTable;
use Exception;
use Oxzion\Encryption\TwoWayEncryption;

class EmailService extends AbstractService
{

    private $table;

    public function __construct($config, $dbAdapter, EmailTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createOrUpdateEmailAccount(&$data)
    {
        $form = new Email();
        $userId = $data['userid'] = AuthContext::get(AuthConstants::USER_ID);
        if ($data['email']) {
            $queryString = "select id,email from email_setting_user";
            $where = "where userid = " . $userId;
            $order = "order by id";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
            if ($resultSet) {
                $emailList = array_column($resultSet->toArray(), 'email', 'id');
                foreach ($emailList as $key => $value) {
                    if ($data['email'] == $value) {
                        $id = $key;
                        $obj = $this->table->get($id, array());
                        if (is_null($obj)) {
                            return 0;
                        }
                        $data = array_merge($obj->toArray(), $data);
                        $data['id'] = $id;
                        $data['userid'] = $userId;
                        if ($data['password']) {
                            $data['password'] = TwoWayEncryption::encrypt($data['password']);
                        }
                        $form->exchangeArray($data);
                        $form->validate();
                        $count = 0;
                        try {
                            $count = $this->table->save($form);
                            if ($count == 0) {
                                $this->rollback();
                                return 0;
                            }
                        } catch (Exception $e) {
                            $this->rollback();
                            return 0;
                        }
                        return $count;
                    }
                }
            }
        }
        if ($data['password']) {
            $data['password'] = TwoWayEncryption::encrypt($data['password']);
        }
        $data['host'] = substr($data['email'], strpos($data['email'], "@") + 1);
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

    public function getEmailAccountsByUserId()
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select id,userid,email,host,isdefault from email_setting_user";
        $where = "where email_setting_user.userid = " . $userId;
        $order = "order by email_setting_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function getEmailAccountByUserId($id)
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select id,userid,email,host,isdefault from email_setting_user";
        $where = "where email_setting_user.userid = " . $userId . " AND email_setting_user.id =" . $id;
        $order = "order by email_setting_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function emailDefault($id)
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select email from ox_user";
        $where = "where id = " . $userId;
        $resultSet = $this->executeQuerywithParams($queryString, $where)->toArray();
        $email = array_column($resultSet, 'email');
        $queryString = "select * from email_setting_user";
        $where = "where userid = " . $userId;
        $order = "order by id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
        $storeData = array();
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        } else {
            $obj = $obj->toArray();
        }
        foreach ($resultSet as $key => $value) {
            if ($value['email'] == $email[0]) {
                $obj['isdefault'] = 1;
                $storeData[] = $obj;
            }
        }
        if ($storeData) {
            $query = $this->multiInsertOrUpdate('email_setting_user', $storeData, array());
        } else {
            return 0;
        }

        $queryString = "select id,userid,email,host,isdefault from email_setting_user";
        $where = "where email_setting_user.userid = " . $userId;
        $order = "order by email_setting_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function deleteEmail($email)
    {
        $id = 0;
        if ($email) {
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $queryString = "select id,email from email_setting_user";
            $where = "where userid = " . $userId;
            $order = "order by id";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
            if ($resultSet) {
                $emailList = array_column($resultSet->toArray(), 'email', 'id');
                foreach ($emailList as $key => $value) {
                    if ($email == $value) {
                        $id = $key;
                    }
                }
            }
            if ($id) {
                $response = $this->deleteEmailAccount($id);
                return array($response);
            }
        }
        return 0;
    }

    public function deleteEmailAccount($id)
    {
        $count = 0;
        try {
            $count = $this->table->delete($id);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
        return $count;
    }

    public function updateEmail($email, $data)
    {
        $id = 0;
        if ($email) {
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $queryString = "select id,email from email_setting_user";
            $where = "where userid = " . $userId;
            $order = "order by id";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
            if ($resultSet) {
                $emailList = array_column($resultSet->toArray(), 'email', 'id');
                foreach ($emailList as $key => $value) {
                    if ($email == $value) {
                        $id = $key;
                    }
                }
            }
            if (isset($data['email'])) {
                $data['host'] = substr($data['email'], strpos($data['email'], "@") + 1);
            }

            if ($id) {
                $response = $this->updateEmailAccount($id, $data);
                return array($response);
            } else {
                $data['password'] = null;
                return $data;
            }

        }
        return 0;
    }

    public function updateEmailAccount($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Email();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
        $data['userid'] = AuthContext::get(AuthConstants::USER_ID);
        if ($data['password']) {
            $data['password'] = TwoWayEncryption::encrypt($data['password']);
        }
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }
}