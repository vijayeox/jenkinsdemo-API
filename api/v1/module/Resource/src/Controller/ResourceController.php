<?php
/**
 * Resource Api
 */
namespace Resource\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Resource\Service\ResourceService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

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

    public function __construct(ResourceService $resourceService, AdapterInterface $dbAdapter)
    {
        $this->resourceService = $resourceService;
        $this->setIdentifierName('resourceId');
        $this->log = $this->getLogger();
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
        $this->log->info(__CLASS__ . "-> Get the resource for the UUID- " . print_r($id, true));
        try {
            $result = $this->resourceService->getResource($id);
            if (!headers_sent()) {
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary");
                header("Content-disposition: attachment; filename=\"" . basename($result) . "\"");
            }
            $fp = @fopen($result, 'rb');
            fpassthru($fp);
            fclose($fp);
            $this->response->setStatusCode(200);
            return $this->response;
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
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
