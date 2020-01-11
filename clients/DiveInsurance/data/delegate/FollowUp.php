<?php
use Oxzion\Db\Persistence\Persistence;

require_once __DIR__ . "/DispatchNotification.php";

class FollowUp extends DispatchNotification
{
    public $template = array();

    public function __construct()
    {
        $this->template = array(
            'Individual Professional Liability' => 'IPL_Renewal_Reminder',
            'Dive Boat' => 'DB_Renewal_Reminder',
            'Dive Store' => 'DS_Renewal_Reminder',
            'EFR' => 'EFR_Renewal_Reminder',
        );

        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $mailOptions = array();
        $response = array();
        $dataArray = $data['data'];
        if (!empty($dataArray)) {
            foreach ($dataArray as $val) {
                if (!is_array($val)) {
                    $val = json_decode($val, true);
                }
                if (!empty($val['email'])) {
                    $val['workflowInstanceId'] = $val['workflowInstanceId'];
                    $val['template'] = $this->template[$val['product']];
                    $val['orgUuid'] = $data['orgId'];
                    $val['orgId'] = $data['orgId'];
                    $template = $val['template'];
                    $val['to'] = $val['email'];
                    $val['subject'] = "Renewal Notification";
                    $val['url'] = $this->baseUrl. '?app=DiveInsurance&params={"type":"Form","url":"workflow/' . $data['workflowId'] . '/startform","workflowInstanceId":"' . $val['workflowInstanceId'] . '"}';
                    $response[] = $this->dispatch($val, $template, $mailOptions);
                }
            }
        }
        return $response;
    }
}
