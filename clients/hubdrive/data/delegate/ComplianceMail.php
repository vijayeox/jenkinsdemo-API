<?php

use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;

class ComplianceMail extends MailDelegate
{

    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Compliance Mail with data- " . print_r($data, true));
        // Add logs for created by id and producer name who triggered submission
        $type = (isset($data['entity_name']) && $data['entity_name'] == 'Excess Liability') ? 'excessLiabilityMail' : 'complianceMail';
        $selectQuery = "Select value FROM applicationConfig WHERE type ='" . $type . "'";
        $mailTo = ($persistenceService->selectQuery($selectQuery))->current()["value"];
        $mailOptions = [];
        $mailOptions['to'] = $mailTo;
        $fileData = array();
        $mailOptions['subject'] = (isset($data['entity_name']) && $data['entity_name'] == 'Excess Liability') ? 'Excess Liability' : 'Independant Contractor Compliance';
        $template = (isset($data['entity_name']) && $data['entity_name'] == 'Excess Liability') ? 'excessLiabilityMail' : 'ICComplianceMail';
        if (isset($data['attachments'])) {
            $data['attachments'] = json_decode($data['attachments'], true);
            foreach ($data['attachments'] as $key => $value) {
                $mailOptions['attachments'][$key] = $value['fullPath'];
            }
        }
        $response = $this->sendMail($data, $template, $mailOptions);
        $this->logger->info("Mail Response" . $response);
        return $data;
    }
}
