<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;

class DispatchPolicy extends MailDelegate {

    public function execute(array $data,Persistence $persistenceService)
    {
        $mailOptions = array();
        $file = array();
        $output = '';
        $mailOptions['to'] = $data['email'];
        $mailOptions['subject'] = 'Certificate Of Insurance';
        $fileData = $data['policy_document'];
        $file = array($fileData);
        $mailOptions['attachments'] = $file;
        $template = 'mailTemplate';
        $response = $this->sendMail($data,$template,$mailOptions);
        return $response;
    }
}
?>