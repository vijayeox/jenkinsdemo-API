<?php
namespace PaymentGateway\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use PaymentGateway\Model\Payment;
use PaymentGateway\Model\PaymentTable;

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
            ->where(array('user_alert_verfication.alert_id' => $data['alert_id'], 'user_alert_verfication.user_id' => $data['user_id']));
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

    public function paymentProcessing()
    {
        // Set variables
        $merchantID = "xxxxxx"; //Converge 6 or 7‐Digit Account ID *Not the 10‐Digit Elavon Merchant ID*
        $merchantUserID = "convergeapi"; //Converge User ID *MUST FLAG AS HOSTED API USER IN CONVERGE UI*
        $merchantPinCode = ""; //Converge PIN (64 CHAR A/N)
        $url = "https://api.demo.convergepay.com/hosted‐payments/transaction_token"; // URL to Converge demo session token server
        //$url = "https://api.convergepay.com/hosted‐payments/transaction_token"; // URL to Converge production session token server
        /*Payment Field Variables*/
// In this section, we set variables to be captured by the PHP file and passed to Converge in the curl request.
        $firstname = $_POST['ssl_first_name']; //Post first name
        $lastname = $_POST['ssl_last_name']; //Post first name
        $amount = $_POST['ssl_amount']; //Post Tran Amount
        //$merchanttxnid = $_POST['ssl_merchant_txn_id']; //Capture user‐defined ssl_merchant_txn_id as POST data
        //$invoicenumber = $_POST['ssl_invoice_number']; //Capture user‐defined ssl_invoice_number as POST data
        //Follow the above pattern to add additional fields to be sent in curl request below.
        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_POST, true); // set POST method
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// Set up the post fields. If you want to add custom fields, you would add them in Converge, and add the field name in the curlopt_postfields string
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "ssl_merchant_id = $merchantID" .
            "&ssl_user_id = $merchantUserID" .
            "&ssl_pin = $merchantPinCode" .
            "&ssl_transaction_type = CCSALE" .
            "&ssl_first_name = $firstname" .
            "&ssl_last_name = $lastname" .
            "&ssl_get_token = Y" .
            "&ssl_add_token = Y" .
            "&ssl_amount = $amount"
        );

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch); // run the curl procss
        curl_close($ch); // Close cURL
        echo $result; //shows the session token.
    }

    public function getPaymentDetails($appId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_payment')
            ->columns(array("*"))
            ->where(array('ox_payment.app_id' => $appId));
        return $this->executeQuery($select)->toArray();
    }
}
