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
        public function addContact($data, $contactService, $userService)
        {
            $params = array();
            $params['first_name'] = isset($data['firstName']) ? $data['firstName'] : null;
            $params['last_name'] = isset($data['lastName']) ? $data['lastName'] : null;
            if (isset($data['phones']) && !empty($data['phones'])) {
                $params['phone_1'] = $data['phones'][0];
            }
            $params['email'] = isset($data['email']) ? $data['email'] : null;
            if (isset($data['accounts']) && !empty($data['accounts'])) {
                $params['company_name'] = $data['accounts']['name'];
            }
            if (isset($data['addresses']) && !empty($data['addresses'])) {
                $params['address_1'] = $data['addresses'][0]['name'];
                $params['address_2'] = isset($data['addresses'][1]['name']) ? $data['addresses'][1]['name'] : null;
            }
            $data['owner']['username'] = isset($data['owner']['username']) ? $data['owner']['username'] : null;
            $data['assignedTo']['username'] = isset($data['assignedTo']['username']) ? $data['assignedTo']['username'] : null;
            $assignedTo = $userService->getUserDetailsbyUserName($data['owner']['username'], array('uuid'));
            $owner = $userService->getUserDetailsbyUserName($data['assignedTo']['username'], array('id'));
            $assignedTo['uuid'] = isset($assignedTo['uuid']) ? $assignedTo['uuid'] : null;
            $params['owner_id'] = isset($owner['id']) ? $owner['id'] : null;
            $params['created_id'] = isset($owner['id']) ? $owner['id'] : null;
            $params['uuid'] = $assignedTo['uuid'];
            try {
                $result = $contactService->createContact($params);
            } catch (Exception $e) {
                return 0;
            }
            if ($result) {
                return array('body'=>$params);
            } else {
                return 0;
            }
        }
    }
