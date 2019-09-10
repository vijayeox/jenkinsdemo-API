<?php
namespace Payment\Service;

use Oxzion\Service\AbstractService;
use Payment\Model\PaymentTable;
use Payment\Model\Payment;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Exception;

class PaymentService extends AbstractService
{
    const ANNOUNCEMENT_FOLDER = "/announcements/";
    /**
    * @ignore ANNOUNCEMENT_FOLDER
    */
    private $table;
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, PaymentTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    /**
    * Create Payment Service
    * @param array $data Array of elements as shown</br>
    * <code> name : string,
    *        status : string,
    *        description : string,
    *        start_date : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *        end_date : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *        media_type : string,
    *        media_location : string,
    *        groups : [{'id' : integer}.....multiple*],
    * </code>
    * @return integer 0|$id of Payment Created
    */
    public function createPayment(&$data)
    {
        $form = new Payment();
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['created_date'] = date('Y-m-d H:i:s');
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
    public function updatePaymentStatus($status, $id)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $data['user_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['alert_id'] = $id;
        $data['status'] = $status;
        $sql = $this->getSqlObject();
        $select = $sql->update('user_alert_verfication')->set($data)
                ->where(array('user_alert_verfication.alert_id' => $data['alert_id'],'user_alert_verfication.user_id' => $data['user_id']));
        $result = $this->executeUpdate($select);
        if ($result->getAffectedRows() == 0) {
            return 0;
        } else {
            return $id;
        }
    }
    /**
    * Update Payment
    * @method PUT
    * @param integer $id ID of Payment to update
    * @param array $data Data Array as Follows:
    * @throws  Exception
    * <code>
    * {
    *  integer id,
    *  string name,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location,
    *  groups : [{'id' : integer}.....multiple]
    * }
    * </code>
    * @return array Returns the Created Payment.
    */
    public function updatePayment($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new Payment();
        $data = array_merge($originalArray, $data);
        $data['id'] = $id;
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
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $id;
    }
    /**
    * Delete Payment
    * @param integer $id ID of Payment to Delete
    * @return int 0=>Failure | $id;
    */
    public function deletePayment($id)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($id, ['org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $sql = $this->getSqlObject();
            $delete = $sql->delete('user_alert_verfication');
            $delete->where(['alert_id' => $id]);
            $result = $this->executeUpdate($delete);
            if ($result->getAffectedRows() == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
        return $count;
    }
    /**
    * GET List Payment
    * @method GET
    * @return array $dataget list of Payments by User
    * <code></br>
    * {
    *  string name,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location,
    *  groups : [{'id' : integer}.....multiple]
    * }
    * </code>
    */
    public function getPayments()
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
                ->from('ox_alert')
                ->columns(array("*"))
                ->where(array('ox_alert.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        return $this->executeQuery($select)->toArray();
    }
}
