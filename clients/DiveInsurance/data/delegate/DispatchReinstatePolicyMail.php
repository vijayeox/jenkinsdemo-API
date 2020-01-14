<?php
use Oxzion\AppDelegate\MailDelegate;

class DispatchReinstatePolicyMail extends MailDelegate
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
        $this->template = array(
            'Individual Professional Liability' => 'CancelPolicyMailTemplate',
            'Dive Boat' => 'CancelPolicyMailTemplate',
            'Dive Store' => 'CancelPolicyMailTemplate');
    }

    protected function dispatch(array $data)
    {
        $this->logger->info("DISPATCH DATA" . print_r($data, true));
        $mailOptions = array();
        $mailOptions['to'] = $data['email'];
        $mailOptions['subject'] = $data['subject'];
        $data['template'] = $this->template[$data['product']];
        $response = $this->sendMail($data, $data['template'], $mailOptions);
        return $response;
    }
}