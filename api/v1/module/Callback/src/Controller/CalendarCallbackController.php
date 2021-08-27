<?php
namespace Callback\Controller;

use Callback\Service\CalendarService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\EmailService;
use Oxzion\Utils\RestClient;

class CalendarCallbackController extends AbstractApiControllerHelper
{
    private $calendarService;
    private $emailService;
    protected $log;
    private $restClient;

    public function setEmailService($emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @ignore __construct
     */
    public function __construct(CalendarService $calendarService, EmailService $emailService, $config)
    {
        $this->calendarService = $calendarService;
        $this->emailService = $emailService;
        $this->log = $this->getLogger();
        $this->restClient = new RestClient($config['calendar']['calendarServerUrl']);
    }

    public function sendMailAction()
    {
        $params = $this->extractPostData();
        $attachments = $this->params()->fromFiles();
        $this->calendarService->setEmailService($this->emailService);
        $response = $this->calendarService->sendMail($params, $attachments);
        if (!$response) {
            return $this->getSuccessResponseWithData(array('Mail Sent'), 201);
        } else {
            return $this->getErrorResponse("Mail Send Failed", 404);
        }
    }

    public function addEventAction()
    {
        $params = $this->extractPostData();
        $this->log->info(print_r($params, true));
        $response = $this->restClient->post('/calendar/server/phpmailer/extras/extract_ics_data/geticsdata.php', $params);
        $this->log->info($response);
        return $this->getSuccessResponseWithData(array('Event Added'), 201);
    }
}
