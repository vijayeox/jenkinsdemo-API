<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use PaymentGateway\Model\Payment;
use PaymentGateway\Model\PaymentTable;
use PaymentGateway\Model\PaymentTransaction;
use PaymentGateway\Model\PaymentTransactionTable;

// use Oxzion\Utils\RestClient;

class PaymentService extends AbstractService
{
    private $table;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, PaymentTable $table, PaymentTransactionTable $transactionTable)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->transactionTable = $transactionTable;
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
                throw new ServiceException("Payment Could not be created", 'could.not.create');
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage() . "-" . $e->getTraceAsString());
            throw $e;
        }
        return $count;
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
            throw new ValidationException("Could not find the payment ID to update", 'could.not.find.payment');
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
                throw new ServiceException("Payment Could not be updated", 'could.not.update');
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage() . "-" . $e->getTraceAsString());
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
                throw new ServiceException("Payment Could not be deleted", 'could.not.delete');
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage() . "-" . $e->getTraceAsString());
            throw $e;
        }
        return $count;
    }

    public function initiatePaymentProcess($appUuid, &$data)
    {
        try {
            $paymentInfo = $this->getPaymentInfoBasedOnGatewayType($appUuid);
            if (count($paymentInfo) > 0) {
                $data['config'] = $paymentInfo;
                $paymentEngine = $this->getPaymentEngine($paymentInfo);
                $initiatePaymentResult = $paymentEngine->initiatePaymentProcess($data);
                $transaction['token'] = $initiatePaymentResult;
                $transaction['data'] = json_encode($data);
                $transactionDetails = $this->createTransactionRecord($paymentInfo['id'], $transaction);
                $data['transaction'] = $transactionDetails;
                $data['token'] = $initiatePaymentResult;
                return $data;
            } else {
                $this->logger->error("Payment Gateway for App - " . $appUuid . " missing!");
                throw (new ServiceException("Payment Gateway for App - " . $appUuid . " missing!", 1));
            }
        } catch (ServiceException $e) {
            $this->logger->error("Payment Initialization has Failed " . $e->getMessage());
            throw new ServiceException("Payment Initialization has Failed, " . $e->getMessage(), 1);
        } catch (Exception $e) {
            $this->logger->error("Payment Initialization has Failed " . $paymentInfo['payment_client'] . " missing!");
            throw (new ServiceException("Payment Initialization has Failed " . $paymentInfo['payment_client'] . " missing!", 1));
        }
    }

    private function getPaymentEngine($paymentInfo)
    {
        try {
            $className = "Oxzion\Payment\\" . $paymentInfo['payment_client'] . "\PaymentEngineImpl";
            if (class_exists($className)) {
                return (new $className($paymentInfo));
            } else {
                throw (new ServiceException("Payment Gateway has not been implement " . $paymentInfo['payment_client'] . " missing!", 1));
            }
        } catch (Exception $e) {
            $this->logger->error("Payment Gateway has not been implement " . $paymentInfo['payment_client'] . " missing!");
            throw (new ServiceException("Payment Gateway has not been implement " . $paymentInfo['payment_client'] . " missing!", 1));
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
            $this->logger->error($e->getMessage() . "-" . $e->getTraceAsString());
            throw $e;
        }
    }

    protected function getPaymentInfoBasedOnGatewayType($appUuid)
    {
        $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        try {
            $select = "SELECT ox_payment.* FROM `ox_payment`
                    INNER JOIN ox_app on ox_app.id = ox_payment.app_id WHERE ox_app.uuid =:appUuid AND ox_payment.server_instance_name =:serverInstanceName AND ox_payment.account_id= :accountId";
            $selectParams = array("appUuid" => $appUuid, "serverInstanceName" => $this->paymentGatewayType, "accountId" => $accountId);
            $gateWay = $this->executeQuerywithBindParameters($select, $selectParams)->toArray();
            if (count($gateWay)) {
                return $gateWay[0];
            } else {
                return $gateWay;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage() . "-" . $e->getTraceAsString());
            throw $e;
        }
    }

    private function createTransactionRecord($paymentId, &$data)
    {
        $form = new PaymentTransaction();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['payment_id'] = $paymentId;
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->transactionTable->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->transactionTable->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage() . "-" . $e->getTraceAsString());
            throw $e;
        }
        return $data;
    }

    public function processPayment($appUuid, $id, &$data)
    {
        $data['app_id'] = $this->getIdFromUuid('ox_app', $appUuid);
        $paymentInfo = $this->getPaymentInfoBasedOnGatewayType($appUuid);
        $select = "SELECT * FROM `ox_payment_transaction` WHERE id =:id";
        $result = $this->executeQuerywithBindParameters($select, array("id" => $id))->toArray();
        if (count($result) > 0) {
            try {
                $paymentEngine = $this->getPaymentEngine($paymentInfo);
                $transactionDetails = $paymentEngine->handleTransaction($data);
                $form = new PaymentTransaction();
                $formdata = array_merge($result[0], $transactionDetails);
                $formdata['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
                $formdata['date_modified'] = date('Y-m-d H:i:s');
                $form->exchangeArray($formdata);
                $form->validate();
                $this->beginTransaction();
                $count = 0;
                try {
                    $count = $this->transactionTable->save($form);
                    if ($count == 0) {
                        $this->rollback();
                        return 0;
                    }
                    $id = $this->transactionTable->getLastInsertValue();
                    $data['id'] = $id;
                    $this->commit();
                } catch (Exception $e) {
                    $this->rollback();
                    $this->logger->error($e->getMessage() . "-" . $e->getTraceAsString());
                    throw $e;
                }
            } catch (ServiceException $e) {
                $this->logger->error("Payment Gateway has not been implement " . $e->getMessage());
                throw (new ServiceException($e->getMessage(), 1));
            } catch (Exception $e) {
                $this->logger->error("Payment Gateway has not been implement " . $paymentInfo['payment_client'] . " missing!");
                throw (new ServiceException("Payment Gateway has not been implement " . $paymentInfo['payment_client'] . " missing!", 1));
            }
            return $formdata;
        } else {
            return 0;
        }
    }
}
