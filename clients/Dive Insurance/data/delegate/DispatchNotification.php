<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;


abstract class DispatchNotification extends MailDelegate {

    protected function dispatch(array $data)
    {
        $mailOptions = array();
        $mailOptions['to'] = $data['email'];
        $mailOptions['subject'] = $data['subject'];
        $template = $data['template'];
        $response = $this->sendMail($data,$template,$mailOptions);
        return $response;
    }
}
?>