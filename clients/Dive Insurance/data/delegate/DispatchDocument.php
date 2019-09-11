<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;


abstract class DispatchDocument extends MailDelegate {

    protected function dispatch(array $data)
    {
        $mailOptions = array();
        $file = array();
        $mailOptions['to'] = $data['email'];
        $mailOptions['subject'] = $data['subject'];
        $crypto = new Crypto();
        $data['policy_document'] = $crypto->decryption($data['policy_document']);
        $fileData = $data['policy_document'];
        $file = array($fileData);
        $mailOptions['attachments'] = $file;
        $template = $data['template'];
        $response = $this->sendMail($data,$template,$mailOptions);
        return $response;
    }
}
?>