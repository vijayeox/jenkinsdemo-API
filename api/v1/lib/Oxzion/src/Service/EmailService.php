<?php
namespace Oxzion\Service;

use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Encryption\TwoWayEncryption;
use Oxzion\Model\Email;
use Oxzion\Model\EmailTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use TheSeer\Tokenizer\Exception;

class EmailService extends AbstractService
{
    private $table;

    public function __construct($config, $dbAdapter, EmailTable $table = null)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createOrUpdateEmailAccount(&$data)
    {
        try {
            $form = new Email();
            $userId = $data['userid'] = AuthContext::get(AuthConstants::USER_ID);
            $data['email'] = isset($data['email']) ? $data['email'] : null;
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
                            $count = $this->table->save($form);
                            if ($count == 0) {
                                $this->rollback();
                                return 0;
                            }
                            return $count;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        if (isset($data['password'])) {
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
                throw new ServiceException("Could not create email account", 'could.not.create');
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function getEmailAccountsByUserId($id = null)
    {
        $accounts = array();
        if (empty($id)) {
            $userId = AuthContext::get(AuthConstants::USER_ID);
        } else {
            $userId = $id;
        }

        $queryString = "select email_setting_user.id, userid,password,email,host,email_setting_user.token,isdefault,ox_email_domain.* from email_setting_user LEFT JOIN ox_email_domain on ox_email_domain.name=email_setting_user.host where email_setting_user.userid = " . $userId;
        $result = $this->executeQuerywithParams($queryString);
        foreach ($result->toArray() as $account) {
            $account['token'] = json_decode($account['token']);
            $account['password'] = TwoWayEncryption::decrypt($account['password']);
            $accounts[] = $account;
        }
        return $accounts;
    }

    public function getEmailAccountsByEmailId($id = null, $pw = false)
    {
        $accounts = array();
        if (empty($id)) {
            return false;
        } else {
            $emailId = $id;
        }

        $queryString = "select email_setting_user.id,userid,password,email,host,isdefault,ox_email_domain.* from email_setting_user LEFT JOIN ox_email_domain on ox_email_domain.name=email_setting_user.host";
        $where = "where email_setting_user.email = '" . $emailId . "'";
        $order = "order by email_setting_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        if ($pw) {
            foreach ($resultSet->toArray() as $account) {
                $account['password'] = TwoWayEncryption::decrypt($account['password']);
                $accounts[] = $account;
            }
        } else {
            foreach ($resultSet->toArray() as $account) {
                if ($account['password']) {
                    $account['authRequired'] = 1;
                } else {
                    $account['authRequired'] = 0;
                }
            }
            $accounts = $account;
        }
        return $accounts;
    }

    public function getEmailAccountById($id)
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select email_setting_user.id,userid,password,email,host,isdefault,ox_email_domain.* from email_setting_user LEFT JOIN ox_email_domain on ox_email_domain.name=email_setting_user.host";
        $where = "where email_setting_user.userid = " . $userId . " AND email_setting_user.id =" . $id;
        $order = "order by email_setting_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        $accounts = array();
        foreach ($resultSet->toArray() as $account) {
            $account['password'] = TwoWayEncryption::decrypt($account['password']);
            $accounts[] = $account;
        }
        return $accounts;
    }

    public function emailDefault($id)
    {
        try {
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
                throw new ServiceException("Could not update the domain", 'could.not.update');
            }

            $queryString = "select id,userid,email,host,isdefault from email_setting_user";
            $where = "where email_setting_user.userid = " . $userId;
            $order = "order by email_setting_user.id";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        } catch (Exception $e) {
            throw $e;
        }
        return $resultSet->toArray();
    }

    public function deleteEmail($email)
    {
        $id = 0;
        try {
            if ($email) {
                $userId = AuthContext::get(AuthConstants::USER_ID);
                $queryString = "select id, email from email_setting_user";
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
        } catch (Exception $e) {
            throw $e;
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
            throw $e;
        }
        return $count;
    }

    public function updateEmail($email, $data)
    {
        $id = 0;
        try {
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
        } catch (Exception $e) {
            throw $e;
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
                throw new ServiceException("Could not update the email account", 'could.not.update');
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }
}
