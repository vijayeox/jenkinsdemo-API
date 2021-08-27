<?php
namespace Callback\Service;

    use Oxzion\Service\AbstractService;
    use Oxzion\Email\EmailClient;
    use Exception;
    use Horde_Exception;

    class CalendarService extends AbstractService
    {
        protected $dbAdapter;
        protected $emailService;
        protected $emailClient;
        
        public function __construct($config)
        {
            parent::__construct($config, null);
            $this->emailClient = new EmailClient();
        }
        public function setEmailService($emailService)
        {
            $this->emailService = $emailService;
        }
        public function setEmailClient($emailClient)
        {
            $this->emailClient = $emailClient;
        }

        public function sendMail($data, $attachment)
        {
            $attachment = isset($attachment['attachment']) ? $attachment['attachment'] : false;
            $userEmail = $data['from'];
            $smtpDetails = $this->emailService->getEmailAccountsByEmailId($userEmail, true)[0];
            $body = $data['body'];
            if (is_array($attachment)) {
                $attachment = array(array(
                    'file'=>$attachment['tmp_name'],
                    'bytes'=>$attachment['size'],
                    'filename'=>$attachment['name'],
                    'type'=>$attachment['type']
                ));
            } else {
                $attachment = array();
            }
            $headers = array(
                'to' => isset($data['to']) ? $data['to'] : null,
                'from' => $data['from'],
                'subject' => $data['subject'],
            );

            $smtpConfig = array(
                'host' => $smtpDetails['smtp_server'],
                'password' => $smtpDetails['password'],
                'port' => $smtpDetails['smtp_port'],
                'secure' => $smtpDetails['smtp_secure'],
                'username' => $data['from'],
            );
            try {
                if ($body != strip_tags($body)) {
                    $response = $this->emailClient->buildAndSendMessage($body, $attachment, $headers, $smtpConfig, $opt=['html'=>true]);
                } else {
                    $response = $this->emailClient->buildAndSendMessage($body, $attachment, $headers, $smtpConfig, $opt=['html'=>false]);
                }
            } catch (Exception $e) {
                $this->logger->error(" Error : ".$e->getMessage(), $e);
                return true;
            }
        }
    }
