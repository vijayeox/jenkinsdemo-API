<?php
/**
* Resource Api
*/
namespace Resource\Controller;

use Zend\Log\Logger;
use Resource\Service\ResourceService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Utils\Query;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
use Oxzion\Controller\AbstractApiControllerHelper;

/**
 * Resource Controller
 */
class ResourceController extends AbstractApiControllerHelper
{
    /**
    * @var ResourceService Instance of Attchment Service
    */
    private $resourceService;
    /**
    * @ignore __construct
    */
    public function __construct(ResourceService $resourceService, Logger $log, AdapterInterface $dbAdapter)
    {
        $this->resourceService = $resourceService;
        $this->setIdentifierName('resourceId');
    }
    /**
    * Create Resource API
    * @api
    * @link /resource
    * @method POST
    */
    public function create($data)
    {
        return $this->getInvalidMethod();
    }
    /**
    * GET Resource API
    * @api
    * @link /resource
    * @method GET
    * @param $id ID of Resource to Delete
    * @return array $data
    * <code>
    * {
    *  integer id,
    *  string file_name,
    *  integer extension,
    *  string uuid,
    *  string type,
    *  dateTime path Full Path of File,
    * }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Resource.
    */
    public function get($id)
    {
        $result = $this->resourceService->getResource($id);
        if (!headers_sent()) {
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . basename($result) . "\"");
        }
        try {
            $fp = @fopen($result, 'rb');
            fpassthru($fp);
            fclose($fp);
            $this->response->setStatusCode(200);
            return $this->response;
        } catch (Exception $e) {
            return $this->getErrorResponse("Resource not Found", 404);
        }
    }
    /**
    * GET List Resource API
    * @api
    * @link /resource
    * @method GET
    * @return Error Response Array
    */
    public function getList()
    {
        return $this->getInvalidMethod();
    }
}
