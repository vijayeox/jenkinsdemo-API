<?php
namespace Alert\Service;

use Alert\Model\Alert;
use Alert\Model\AlertTable;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;

class AlertService extends AbstractService
{
    const ANNOUNCEMENT_FOLDER = "/announcements/";
    /**
     * @ignore ANNOUNCEMENT_FOLDER
     */
    private $table;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AlertTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    /**
     * Create Alert Service
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
     * @return integer 0|$id of Alert Created
     */
    public function createAlert(&$data)
    {
        $form = new Alert();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['status'] = $data['status'] ? $data['status'] : 1;
        $data['created_date'] = date('Y-m-d H:i:s');
        $this->logger->info("\n Data modified before create- " . json_encode($data, true));
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
            throw $e;
        }
        return $count;
    }

    public function updateAlertStatus($status, $id)
    {
        try {
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
        } catch (Exception $e) {
            throw $e;
        }
        if ($result->getAffectedRows() == 0) {
            return 0;
        } else {
            return $id;
        }
    }

    /**
     * Update Alert
     * @method PUT
     * @param integer $id ID of Alert to update
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
     * @return array Returns the Created Alert.
     */
    public function updateAlert($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new Alert();
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
                throw new ServiceException("Could not update the Alert", 'could.not.create');
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $id;
    }

    /**
     * Delete Alert
     * @param integer $id ID of Alert to Delete
     * @return int 0=>Failure | $id;
     */
    public function deleteAlert($id)
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
            throw $e;
        }
        return $count;
    }

    /**
     * GET List Alert
     * @method GET
     * @return array $dataget list of Alerts by User
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
    public function getAlerts()
    {
        try {
            $sql = $this->getSqlObject();
            $select = $sql->select()
                ->from('ox_alert')
                ->columns(array("*"))
                ->where(array('ox_alert.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        } catch (Exception $e) {
            throw $e;
        }
        return $this->executeQuery($select)->toArray();
    }
}
