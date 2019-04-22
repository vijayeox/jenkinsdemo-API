<?php
namespace Callback\Service;

    use Bos\Service\AbstractService;
    use Zend\Log\Logger;
    use Oxzion\Email\EmailClient;
    use Exception;
    use Horde_Exception;

    class CalendarService extends AbstractService
    {
        protected $dbAdapter;
        protected $emailService;

        public function __construct($config, Logger $log)
        {
            parent::__construct($config, null, $log);
        }
        public function setEmailService($emailService){
            $this->emailService = $emailService;
        }

        public function sendMail($data,$attachment){
            $emailClient = new EmailClient();
            $attachment = $attachment['attachment'];
            $userEmail = $data['from'];
            $smtpDetails = $this->emailService->getEmailAccountsByEmailId($userEmail)[0];
            $body = $data['body'];
            if(is_array($attachment)){
                $attachment = array(array(
                    'file'=>$attachment['tmp_name'],
                    'bytes'=>$attachment['size'],
                    'filename'=>$attachment['name'],
                    'type'=>$attachment['type']
                ));
            }else {
                $attachment = array();
            }
            $headers = array(
                'to' => $data['to'],
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
                $response = $emailClient->buildAndSendMessage($body,$attachment,$headers,$smtpConfig);
            } catch(Exception $e) {
                return true;
            }
        }
    }
    ?>