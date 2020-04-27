<?php
/**
* Import Api
*/
namespace Import\Controller;

use Import\Service\ImportService;
use Zend\Db\Adapter\AdapterInterface;
use Import\Model\ImportTable;
use Import\Model\Import;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\Query;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;

/**
 * Import Controller
 */
class ImportController extends AbstractApiController
{
    /**
    * @var ImportService Instance of Attchment Service
    */
    private $ImportService;
    /**
    * @ignore __construct
    */
    public function __construct(ImportService $ImportService)
    {
        parent::__construct(null, ImportController::class);
        $this->ImportService = $ImportService;
        $this->setIdentifierName('ImportId');
    }
    /**
    * Create Import API
    * @api
    * @link /Import
    * @method POST
    * @param array $data Array of elements as shown</br>
    * <code> TYPE : string,
    *  string file_name,
    *  integer extension,
    *  string uuid,
    *  string type,
    *  dateTime path Full Path of File,
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Import.</br>
    * <code> status : "success|error",
    *        data : array Created Import Object
    * </code>
    */
    public function create($data)
    {
        $files = $this->params()->fromFiles();
        try {
            if (!isset($files)) {
                return $this->getSuccessResponseWithData(array("filename"=> ''), 201);
            } else {
                $data = $this->ImportService->upload($data,$files);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    public function get($id)
    {
        return $this->getInvalidMethod();
    }

    public function getList()
    {
        return $this->getInvalidMethod();
    }
}
