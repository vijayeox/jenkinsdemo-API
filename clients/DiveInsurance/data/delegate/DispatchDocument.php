<?php
use Oxzion\AppDelegate\MailDelegate;

abstract class DispatchDocument extends MailDelegate
{

    public function __construct(){
        parent::__construct();
    }
    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    protected function dispatch(array $data)
    {
        $mailOptions = array();
        $fileData = array();
        $mailOptions['to'] = $data['email'];
        $mailOptions['subject'] = $data['subject'];
        if (isset($data['document'])) {
            $mailOptions['attachments'] = $data['document'];
            unset($data['document']);
        }
        $this->logger->info("ATTACHMENTS LIST ".print_r($mailOptions,true));
        if(isset($data['template'])){
            $template = $data['template'];
            $response = $this->sendMail($data, $template, $mailOptions);
            $this->logger->info("Mail Response" . $response);
            return $response;
        }
    }
}
