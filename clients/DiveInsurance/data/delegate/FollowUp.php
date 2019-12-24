<?php
use Oxzion\Db\Persistence\Persistence;

require_once __DIR__ . "/DispatchNotification.php";

class FollowUp extends DispatchNotification
{
    public $template = array();

    public function __construct()
    {
        $this->template = array (
            'Individual Professional Liability' => 'COIRenewelReminderMailTemplate');
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $mailOptions = array();
        $response = array();
        if (!empty($data)) {
            foreach ($data as $val) {
                if (!is_array($val)) {
                    $val = json_decode($val, true);
                }
                if (!empty($val['email'])) {
                    $val['template'] = $this->template[$val['product']];
                    $val['orgUuid'] = $data['orgId'];
                    $template = $val['template'];
                    $val['to'] = $val['email'];
                    $val['subject'] = "Renewal Notification";
                    $response[] = $this->dispatch($val, $template, $mailOptions);
                }
            }
        }
        return $response;
    }
}
