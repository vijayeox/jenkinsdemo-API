<?php

use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
class SendMailtoHub extends MailDelegate
{
    use FileTrait;
    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Excess Mail with data- " . print_r($data, true));
        $fileData = $this->getFile($data['fileId'],false,$data['accountId']);
        $data = array_merge($data,$fileData['data']);
        // Add logs for created by id and producer name who triggered submission
        $mailOptions = [];
        $mailOptions['to'] = $data['HubAMmailId'];
        $fileData = array();
        $mailOptions['subject'] = 'Quote Document';
        $template = 'QuoteDocumentMail';
        if (isset($data['attachments'])) {
            $data['attachments'] = json_decode($data['attachments'], true);
            foreach ($data['attachments'] as $key => $value) {
                if($key == "quote_pdf"){
                    $mailOptions['attachments'][] = $value['fullPath'];
                }
            }
        }
        $response = $this->sendMail($data, $template, $mailOptions);
        $this->logger->info("Mail Response" . $response);
        return $data;
    }
}
