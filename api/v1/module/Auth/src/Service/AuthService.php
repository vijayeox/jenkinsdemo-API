<?php
namespace Auth\Service;

use Bos\Service\AbstractService;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class AuthService extends AbstractService {

	private $table;

	public function __construct($config, $dbAdapter, $table = null)
    {
        parent::__construct($config, $dbAdapter);
        if ($table) {
            $this->table = $table;
        }
    }

    public function getApiSecret($apiKey)
    {
        $queryString = "select secret from ox_api_key";
        $where = 'where api_key = "'.$apiKey.'"';
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $resultSet->toArray();
    }

}