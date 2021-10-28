<?php
namespace Oxzion\Insurance\InsureLearn;

use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Utils\RestClient;
use Oxzion\Utils\ValidationUtils;
use Oxzion\Insurance\InsuranceEngine;

class InsureLearnEngineImpl implements InsuranceEngine
{
    private $config;
    private $client;
    private $debug = false;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
    private function getConfig()
    {
        return $this->config['insurelearn'];
    }
    public function setConfig($data)
    {
        $this->client = new RestClient($this->getConfig()['olpSystem']);
    }
    private function makeRequest(string $entity, array $data, string $action = 'get')
    {
        if (empty($data)) return [];
        try {
            $credentials = ['ILSUSER' => $this->getConfig()['ILSUSER'], 'ILSPASSWD' => $this->getConfig()['ILSPASSWD']];
            $response = $this->client->get("api/$entity/$action/?".http_build_query($data + $credentials));
            if ($this->debug) {
                echo "<pre>";print_r($response);
            }
            $response = $this->cleanResponse($response);
            if ($this->debug) {
                echo "<pre>";print_r($response);
            }
            if (isset(current($response)['code']) && isset(current($response)['message'])) {
                throw new \Exception(current($response)['message'], 404);
            }
        } catch (\Exception $e) {
            echo "<pre>";print_r($e->getMessage());exit;
            $response = [];
        }
        return $response;
    }
    public function cleanResponse($response)
    {
        $response = \Oxzion\Utils\XMLUtils::xmlToArray($response, true);
        // echo "<pre>";print_r($response);exit;
        return $response;
    }

    public function search(string $entity, array $data)
    {
        // $this->debug = true;
        switch ($entity) {
            case 'userData':
                $searchField = empty($data['loginID']) ? 'userData' : 'loginID';
                $searchValue = empty($data['loginID']) ? $data['userData'] : $data['loginID'];
                $user = $this->makeRequest('user', [
                    $searchField => $searchValue,
                    'detail' => 'extra'
                ]);
                if (empty($user['userProfiles'])) {
                    $searchField = empty($data['loginID']) ? 'loginID' : 'userData';
                    $user = $this->makeRequest('user', [
                        $searchField => $searchValue,
                        'detail' => 'extra'
                    ]);
                }
                return empty($user['userProfiles']) ? [] : $user;
                break;
            case 'groupName':
                $groups = $this->makeRequest('group', [
                    'groupTypeID' => !empty($data['groupTypeID']) ? $data['groupTypeID'] : 2,
                    'groupName' => $data['groupName'],
                    'detail' => 'extra'
                ]);
                if (!empty($groups['groups']) && isset($groups['groups']['group'])) {
                    return ['group' => $groups['groups']['group']];
                }
                $groups = $this->makeRequest('group', [
                    'groupTypeID' => !empty($data['groupTypeID']) ? $data['groupTypeID'] : 2,
                    'groupID' => 'ALL',
                    'detail' => 'extra'
                ]);
                foreach ($groups['groups']['group'] as $group) {
                    if (
                        !empty($group['@attributes']['userData']) && (strpos(strtolower(trim($group['@attributes']['userData'])), strtolower(trim($data['groupName']))) !== false) || 
                        !empty($group['@attributes']['groupName']) && (strpos(strtolower(trim($group['@attributes']['groupName'])), strtolower(trim($data['groupName']))) !== false)
                    ) {
                        break;
                    }
                    $group = [];
                }
                return empty($group) ? [] : ['group' => $group];
                break;
            default:
                $response = $this->makeRequest($entity, $data);
                break;
        }
        // echo "<pre>";print_r($response);exit;
        return $response;
    }

    public function create(string $entity, array $data)
    {
        // $this->debug = true;
        switch ($entity) {
            default:
                $response = $this->makeRequest($entity, $data, 'put');
                break;
        }
        // echo "<pre>";print_r($response);exit;
        return $response;
    }

    // $this->search('user', ['loginID' => 'lisa.paul@hubinternational.com']);
    // $this->getSsoLink($this->getConfig()['ILSUSER'], $this->getConfig()['ILSPASSWD']);
    public function getSsoLink(string $username, string $password = 'Welcome2eox!')
    {
        $response = $this->makeRequest('session', [
            'ILSUSER' => $username,
            'ILSPASSWD' => $password
        ], 'login');
        $baseUrl = $this->getConfig()['olpSystem'];
        $launchUrl = $baseUrl . "api/session/launch/?" . http_build_query([
            'uuid' => current($response['success'])['uuid'],
            'launchPoint' => $baseUrl . "employee.jsp"
        ]);
        return $launchUrl;
    }

    public function perform(string $entity, array $data, string $type)
    {
        // $this->debug = true;
        $response = $this->makeRequest($entity, $data, $type);
        // echo "<pre>";print_r($response);exit;
        return $response;
    }

}