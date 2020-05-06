<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Messaging\MessageProducer;

abstract class DispatchDocument extends MailDelegate {

    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    protected function dispatch(array $data)
    {
        $mailOptions = array();
        $mailOptions['to'] = $data['email'];
        $mailOptions['subject'] = $data['subject'];
        if(isset($data['document'])){
            $mailOptions['attachments'] = $data['document'];
            unset($data['document']);
        }
        if(isset($data['template'])){
            $template = $data['template'];
            $response = $this->sendMail($data, $template, $mailOptions);
            $this->logger->info("Mail Response" . $response);
            return $response;
        }
    }
}
?>