<?php
namespace Callback\Controller;

    use Zend\Log\Logger;
    use Oxzion\Controller\AbstractApiControllerHelper;
    use Oxzion\ValidationException;
    use Zend\Db\Adapter\AdapterInterface;
    use Oxzion\Utils\RestClient;
    use Callback\Service\CalendarService;
    use Oxzion\Service\EmailService;
    
    class CalendarCallbackController extends AbstractApiControllerHelper {

        private $calendarService;
        private $emailService;
        protected $log;
        private $restClient;

        public function setEmailService($emailService){
            $this->emailService = $emailService;
        }

        // /**
        // * @ignore __construct
        // */
        public function __construct(CalendarService $calendarService, EmailService $emailService,Logger $log, $config) {
            $this->calendarService = $calendarService;
            $this->emailService = $emailService;
            $this->log = $log;
            $this->restClient = new RestClient($config['calendar']['calendarServerUrl']);
        }

        private function convertParams(){
           $params = json_decode(file_get_contents("php://input"),true);

           if(!isset($params)){
                $params = $this->params()->fromPost();          
           }
            return $params;
        }
        public function sendMailAction() {
            $params = $this->params()->fromPost();
            $attachments = $this->params()->fromFiles();
            $this->calendarService->setEmailService($this->emailService);
            $response = $this->calendarService->sendMail($params,$attachments);
            if(!$response) {
                return $this->getSuccessResponseWithData(array('Mail Sent'),201);
            } else {
                return $this->getErrorResponse("Mail Send Failed", 404);
            }
        }

        public function addEventAction() {
            $params = $this->convertParams();
            $this->log->info(__CLASS__.print_r($params, true));
            $response = $this->restClient->post('/calendar/server/phpmailer/extras/extract_ics_data/geticsdata.php', $params);
            print_r($response);
            return $this->getSuccessResponseWithData(array('Event Added'),201);
        }


    }