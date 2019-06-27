<?php
namespace Callback\Service;

    use Oxzion\Auth\AuthConstants;
    use Oxzion\Auth\AuthContext;
    use Oxzion\Service\AbstractService;
    use Oxzion\ValidationException;
    use Oxzion\Utils\RestClient;
    use Zend\Log\Logger;
    use Exception;

    class CRMService extends AbstractService
    {
        protected $dbAdapter;

        public function __construct($config, Logger $log)
        {
            parent::__construct($config, null, $log);
        }
        public function addContact($data,$contactService,$userService){
            $params = array();
            $params['first_name'] = $data['firstName'];
            $params['last_name'] = $data['lastName'];
            if(isset($data['phones']) && !empty($data['phones'])){
                $params['phone_1'] = $data['phones'][0];
            }
            $params['email'] = $data['email'];
            if(isset($data['accounts']) && !empty($data['accounts'])) {
                $params['company_name'] = $data['accounts'][0]['name'];
            }
            if(isset($data['addresses']) && !empty($data['addresses'])) {
                $params['address_1'] = $data['addresses'][0]['name'];
                $params['address_2'] = $data['addresses'][1]['name'];
            }
            $assignedTo = $userService->getUserDetailsbyUserName($data['owner']['username'],array('uuid'));
            $owner = $userService->getUserDetailsbyUserName($data['assignedTo']['username'],array('id'));
            $params['owner_id'] = $owner['id'];
            $params['created_id'] = $owner['id'];
            $params['uuid'] = $assignedTo['uuid'];
            try {
                $result = $contactService->createContact($params);
            } catch (Exception $e){
                return 0;
            }
            if($result){
                return array('body'=>$params);
            } else {
                return 0;
            }
        }
    }
    ?>