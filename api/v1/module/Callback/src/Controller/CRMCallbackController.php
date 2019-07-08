<?php
namespace Callback\Controller;

    use Zend\Log\Logger;
    use Oxzion\Controller\AbstractApiControllerHelper;
    use Oxzion\ValidationException;
    use Zend\Db\Adapter\AdapterInterface;
    use Oxzion\Utils\RestClient;
    use Callback\Service\CRMService;
    use Contact\Service\ContactService;
    use Oxzion\Service\UserService;

    class CRMCallbackController extends AbstractApiControllerHelper {

        private $crmService;
        // /**
        // * @ignore __construct
        // */
        public function __construct(CRMService $crmService,ContactService $contactService,UserService $userService, Logger $log) {
            $this->crmService = $crmService;
            $this->contactService = $contactService;
            $this->userService = $userService;
        }
        
        public function setCRMService($crmService){
            $this->crmService = $crmService;
        }

        protected function convertParams(){
           $params = json_decode(file_get_contents("php://input"),true);
           if(!isset($params)){
                 $params = $this->params()->fromPost();          
                 if(!is_object($params)){
                    if(key($params)){
                            $params = json_decode(key($params),true);
                    }
                }
           }
            return $params;
        }

        public function addContactAction() {
            $params = $this->convertParams();
            $response = $this->crmService->addContact($params,$this->contactService,$this->userService);
            if($response){
                return $this->getSuccessResponseWithData($response['body'],201);
            }
            return $this->getErrorResponse("Contact Creation Failed", 404);
        }

        public function addCampaignAction() {
            $params = $this->convertParams();
            $response = $this->crmService->addContact($params,$this->contactService,$this->userService);
            if($response){
                return $this->getSuccessResponseWithData($response['body'],201);
            }
            return $this->getErrorResponse("Contact Creation Failed", 404);
        }
    }