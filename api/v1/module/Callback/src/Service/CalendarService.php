<?php
namespace Callback\Service;

    use Oxzion\Service\AbstractService;
    use Zend\Log\Logger;
    use Oxzion\Email\EmailClient;
    use Exception;
    use Horde_Exception;

    class CalendarService extends AbstractService
    {
        protected $dbAdapter;
        protected $emailService;
        private $logClass;

        public function __construct($config, Logger $log)
        {
            parent::__construct($config, null, $log);
            $this->logClass = __CLASS__;
        }
        public function setEmailService($emailService){
            $this->emailService = $emailService;
        }

        public function sendMail($data,$attachment){
            $emailClient = new EmailClient();
            $attachment = isset($attachment['attachment']) ? $attachment['attachment'] : false;
            $userEmail = $data['from'];
            $smtpDetails = $this->emailService->getEmailAccountsByEmailId($userEmail,true)[0];
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
                'to' => isset($data['to']) ? $data['to'] : NULL,
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
                if($body != strip_tags($body)){
                    $response = $emailClient->buildAndSendMessage($body,$attachment,$headers,$smtpConfig,$opt=['html'=>true]);
                } else {
                    $response = $emailClient->buildAndSendMessage($body,$attachment,$headers,$smtpConfig,$opt=['html'=>false]);
                }
            } catch(Exception $e) {
                $this->logger->err($this->logClass . " Error : ".$e->getMessage());
                return true;
            }
        }
    }
    ?>