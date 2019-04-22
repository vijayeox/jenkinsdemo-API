<?php
namespace Callback\Controller;

    use Zend\Log\Logger;
    use Oxzion\Controller\AbstractApiControllerHelper;
    use Bos\ValidationException;
    use Zend\Db\Adapter\AdapterInterface;
    use Oxzion\Utils\RestClient;
    use Callback\Service\CalendarService;
    use Oxzion\Service\EmailService;

    class CalendarCallbackController extends AbstractApiControllerHelper {

        private $calendarService;
        private $emailService;
        protected $log;

        public function setEmailService($emailService){
            $this->emailService = $emailService;
        }

        // /**
        // * @ignore __construct
        // */
        public function __construct(CalendarService $calendarService, EmailService $emailService,Logger $log) {
            $this->calendarService = $calendarService;
            $this->emailService = $emailService;
            $this->log = $log;
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
    }