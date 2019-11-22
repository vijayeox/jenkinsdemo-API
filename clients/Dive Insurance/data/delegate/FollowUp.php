<?php
use Oxzion\Db\Persistence\Persistence;

require_once __DIR__ . "/DispatchNotification.php";

class FollowUp extends DispatchNotification
{

    public $template = array();

    public function __construct()
    {
        $this->template = array(
            'Individual Professional Liability' => 'COIRenewelReminderMailTemplate');
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $mailOptions = array();
        if (!empty($data)) {
            foreach ($data as $val) {
                //check if the user has email ID
                if (!empty($val['email'])) {
                    $val['template'] = $this->template[$val['product']];
                    $template = $val['template'];
                    $mailOptions['to'] = $val['email'];
                    $mailOptions['subject'] = $val['subject'];
                    $response[] = $this->dispatch($val, $template, $mailOptions);
                }
            }
        }
        return $response;
    }

}
