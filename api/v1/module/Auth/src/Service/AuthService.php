<?php
namespace Auth\Service;

use Exception;
use function GuzzleHttp\json_decode;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\ArrayUtils;

class AuthService extends AbstractService
{
    private $table;
    private $userService;
    private $userCacheService;
    public function __construct($config, $dbAdapter, $userService, $userCacheService)
    {
        parent::__construct($config, $dbAdapter);
        $this->userService = $userService;
        $this->userCacheService = $userCacheService;
    }

    public function getApiSecret($apiKey)
    {
        $queryString = "select secret from ox_api_key";
        $where = 'where api_key = "' . $apiKey . '"';
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $resultSet->toArray();
    }
}
