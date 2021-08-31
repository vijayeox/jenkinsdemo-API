<?php

use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;

class ExcessMail extends MailDelegate
{

    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Excess Mail with data- " . print_r($data, true));
        // Add logs for created by id and producer name who triggered submission
        $selectQuery = "Select value FROM applicationConfig WHERE type ='excessLiabilityMail'";
        $mailTo = ($persistenceService->selectQuery($selectQuery))->current()["value"];
        $mailOptions = [];
        $mailOptions['to'] = $mailTo;
        $fileData = array();
        $mailOptions['subject'] = 'HUB Drive Excess Liability Shield';
        $template = 'excessLiabilityMail';
        if (isset($data['attachments'])) {
            $data['attachments'] = json_decode($data['attachments'], true);
            foreach ($data['attachments'] as $key => $value) {
                $mailOptions['attachments'][$key] = $value['fullPath'];
            }
        }
        if(isset($data['desiredPolicyEffectiveDate'])) {
            $data['desiredPolicyEffectiveDateFormatted'] = isset($data['desiredPolicyEffectiveDate']) ? explode('T',$data['desiredPolicyEffectiveDate'])[0] : null;
        }
        $response = $this->sendMail($data, $template, $mailOptions);
        $this->logger->info("Mail Response" . $response);
        return $data;
    }
}
