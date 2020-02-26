<?php
namespace PaymentGateway\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use PaymentGateway\Model\Payment;
use PaymentGateway\Model\PaymentTable;

// use Oxzion\Utils\RestClient;

class PaymentService extends AbstractService
{
    private $table;
    /**
     * @ignore __construct
     */

    public function __construct($config, $dbAdapter, PaymentTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->paymentGatewayType = $this->config['paymentGatewayType'];
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
    public function createPayment(&$data, $appId)
    {
        $form = new Payment();
        $data['app_id'] = $this->getIdFromUuid('ox_app', $appId);
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
            $this->logger->error($e->getMessage(), $e);
            throw $e;
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
    public function updatePayment($id, &$data, $appUuid)
    {
        $data['app_id'] = $this->getIdFromUuid('ox_app', $appUuid);
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
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $id;
    }
    
    /**
     * Delete Payment
     * @param integer $id ID of Payment to Delete
     * @return int 0=>Failure | $id;
     */
    public function deletePayment($id, $appUuid)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($id, ['app_id' => $this->getIdFromUuid('ox_app', $appUuid)]);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    public function initiatePaymentProcess($appUuid, $data)
    {
        $paymentInfo = $this->getPaymentInfoBasedOnGatewayType($appUuid, $this->paymentGatewayType);
        if (!empty($paymentInfo)) {
            $paymentConfigInfo = json_decode($paymentInfo[0]['payment_config']);

            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_URL, $paymentInfo[0]['api_url']); // set url to post to
            curl_setopt($ch, CURLOPT_POST, true); // set POST method
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // Set up the post fields. If you want to add custom fields, you would add them in Converge, and add the field name in the curlopt_postfields string
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                "ssl_merchant_id=$paymentConfigInfo->merchant_id" .
                "&ssl_user_id=$paymentConfigInfo->user_id" .
                "&ssl_pin=$paymentConfigInfo->pincode" .
                "&ssl_transaction_type=CCSALE" .
                "&ssl_first_name=" . $data['firstname'] .
                "&ssl_last_name=" . $data['lastname'] .
                "&ssl_get_token=Y" .
                "&ssl_add_token=Y" .
                "&ssl_amount=" . $data['amount']
            );
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            $result = curl_exec($ch); // run the curl procss
            curl_close($ch); // Close cURL
            // echo $result; //shows the session token.
            $resultData = $this->createTransactionRecord($paymentInfo[0]['id'], $result, json_encode($data));
            return $result;
        }
    }

    public function getPaymentDetails($appId)
    {
        try {
            $select = "SELECT * FROM `ox_payment` WHERE app_id =:appId";
            $selectParams = array("appId" => $this->getIdFromUuid('ox_app', $appId));
            $result = $this->executeQuerywithBindParameters($select, $selectParams)->toArray();
            return $result;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    private function getPaymentInfoBasedOnGatewayType($appUuid, $gatewayType)
    {
        try {
            $select = "SELECT ox_payment.* FROM `ox_payment`
                    INNER JOIN ox_app on ox_app.id = ox_payment.app_id WHERE ox_app.uuid =:appUuid AND ox_payment.server_instance_name =:serverInstanceName";
            $selectParams = array("appUuid" => $appUuid, "serverInstanceName" => $gatewayType);
            return $this->executeQuerywithBindParameters($select, $selectParams)->toArray();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    private function createTransactionRecord($paymentId, $transactionToken, $data)
    {
        $this->beginTransaction();
        try {
            $insert = "INSERT INTO `ox_payment_trasaction` (`payment_id`,`transaction_id`,`data`) VALUES(:paymentId,:transactionToken,:jsonData);";
            $insertParams = array("paymentId" => $paymentId, "transactionToken" => $transactionToken, "jsonData" => $data);
            $result = $this->executeUpdateWithBindParameters($insert, $insertParams);
            $this->commit();
            return $result->getAffectedRows();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }
}
