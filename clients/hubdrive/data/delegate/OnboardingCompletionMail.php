<?php

use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AppDelegateTrait;
use Oxzion\AppDelegate\HttpClientTrait;

class OnboardingCompletionMail extends MailDelegate
{

    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Onboarding Completion Mail with data- " . print_r($data,true));
        $mailOptions = array();
        $mailOptions['to'] = 'support@eoxvantage.com';
        $mailOptions['subject'] = 'Onboarding Completion';
        $template = 'OnboardingCompletionMail';
        if (isset($data['attachments'])) {
            $data['attachments'] = json_decode($data['attachments'],true);
            foreach ($data['attachments'] as $key => $value) {
                $mailOptions['attachments'][$key] = $value['fullPath'];
            }
        }
        $response = $this->sendMail($data, $template, $mailOptions);
            $this->logger->info("Mail Response" . $response);
        return $data;
    }
}
