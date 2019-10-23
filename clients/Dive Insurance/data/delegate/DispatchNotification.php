<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
use Oxzion\DelegateException;

abstract class DispatchNotification extends MailDelegate {

	public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    protected function dispatch(array $data)
    {
        $this->logger->info("DISPATCH DATA".print_r($data,true));
       
   	    $mailOptions = array();
        $mailOptions['to'] = $data['email'];
        $mailOptions['subject'] = $data['subject'];
        $template = $data['template'];
        $response = $this->sendMail($data,$template,$mailOptions);
	    return $response;
    }
}
?>