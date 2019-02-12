<?php
namespace Contact\Service;

use Bos\Service\AbstractService;
use Contact\Model\ContactTable;
use Contact\Model\Contact;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class ContactService extends AbstractService
{

    private $table;

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
    public function createContact(&$data)
    {
        $form = new Contact();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['owner_id'] = (isset($data['owner_id'])) ? $data['owner_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
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
            return 0;
        }
        return $count;
    }

    public function updateContact($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Contact();
        $data = array_merge($obj->toArray(), $data);
        $data['id'] = $id;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['owner_id'] = ($data['owner_id']) ? $data['owner_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
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

    public function deleteContact($id)
    {
        $count = 0;
        try {
            $count = $this->table->delete($id);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
        }
        return $count;
    }

    public function getContactByOwnerId()
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select * from ox_contact";
        $where = "where owner_id = " . $userId . " ";
        $order = "order by first_name";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function getContactByOrgId()
    {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $queryString = "select * from ox_contact";
        $where = "where org_id = " . $orgId . " ";
        $order = "order by first_name";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }
}