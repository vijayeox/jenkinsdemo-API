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
        $data['org_id'] = (isset($data['org_id']))?$data['org_id']:AuthContext::get(AuthConstants::ORG_ID);
        $data['owner_id'] = (isset($data['owner_id'])) ? $data['owner_id'] : AuthContext::get(AuthConstants::USER_ID);
        $data['created_id'] = (isset($data['user_id']))?$data['user_id']:AuthContext::get(AuthConstants::USER_ID);
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
            $this->rollback();
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
        $queryString1 = "select * from ox_contact";
        $where1 = "where org_id = " . $orgId . "";
        $order1 = "order by first_name asc";
        $resultSet1 = $this->executeQuerywithParams($queryString1, $where1, null, $order1);

        //Code to get the list of all the contact information from the user
        $queryString2 = "Select firstname as first_name, lastname as last_name, name, email, status, country, date_of_birth, designation, phone as phone_1, gender, website, timezone, date_of_join from ox_user";
        $where2 = "where orgid = " . $orgId . "";
        $order2 = "order by firstname asc";
        $resultSet2 = $this->executeQuerywithParams($queryString2, $where2, null, $order2);

        return $resultSet = ['myContact' => ($resultSet1->toArray()), 'orgContact' => ($resultSet2->toArray())];
    }

    public function getContacts($column, $filter=null){
        // filter criteria, column control - all or name
        // filter for searching
        // print_r('col: '.gettype($column));
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $orgId = AuthContext::get(AuthConstants::ORG_ID);

        $queryString1 = "SELECT * from (";

        if($column == "-1"){
            $queryString2 = "SELECT id as contact_id, null as user_id, first_name, last_name, phone_1, phone_list, email, email_list from ox_contact";
        } else {
            $queryString2 = "SELECT id as contact_id, null as user_id, first_name, last_name from ox_contact";
        }
        $where1 = " WHERE owner_id = " . $userId . " ";

        if($filter == null){
            $and1  = '';
        } else {
            $and1 = " AND (LOWER(first_name) like '%".$filter."%' OR LOWER(last_name) like '%".$filter."%' OR LOWER(email) like '%".$filter."%' OR lower(phone_1) like '%".$filter."%')";
        }

        $union = " UNION ";

        if($column == "-1"){
            $queryString3 = "SELECT null as contact_id, uuid as user_id, firstname as first_name, lastname as last_name, phone as phone_1, null as phone_list, email, null as email_list from ox_user";
        } else {
            $queryString3 = "SELECT null as contact_id, uuid as user_id, firstname as first_name, lastname as last_name from ox_user";
        }

        $where2 = " WHERE orgid = " . $orgId . "";

        if($filter == null){
            $and2 = '';
        } else {
            $and2 = " AND (LOWER(firstname) like '%".$filter."%' OR LOWER(lastname) like '%".$filter."%' OR LOWER(email) like '%".$filter."%')";
        }

        $queryString4 = ") as a ORDER BY a.first_name, a.last_name";

        $finalQueryString = $queryString1.$queryString2.$where1.$and1.$union.$queryString3.$where2.$and2.$queryString4;
        $resultSet = $this->executeQuerywithParams($finalQueryString);
        $resultSet = $resultSet->toArray();
        $myContacts = array();
        $orgContacts = array();
        foreach ($resultSet as $key => $row) {
            if($row['contact_id'] != null){
                array_push($myContacts, $row);
            } else if($row['user_id'] != null){
                array_push($orgContacts, $row);
            }

        }
        return $resultSet1 = ['myContacts' => $myContacts, 'orgContacts' => $orgContacts];

    }
}
