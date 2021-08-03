<?php
namespace Prehire\Controller;

use Oxzion\Controller\AbstractApiController;
use Prehire\Service\PrehireService;
use Oxzion\ValidationException;
use Prehire\Model\Prehire;
use Prehire\Model\PrehireTable;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

class PrehireController extends AbstractApiController
{
    private $prehireService;
    /**
     * @ignore __construct
     */
    public function __construct(PrehireTable $table, PrehireService $prehireService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Prehire::class);
        $this->prehireService = $prehireService;
        $this->setIdentifierName('prehireId');
    }

    /**
     * Create Prehire API
     * @api
     * @link /prehire/$implementation/$referenceId
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *      request_type : string
     *      request : string
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Payment.
     */
    public function create($data)
    {
        $params = array_merge($this->extractPostData(),$this->params()->fromRoute());
        $this->log->info("Create Prehire with Data - " . json_encode($params, true));
        try {
            $this->prehireService->createRequest($params);
            return $this->getSuccessResponseWithData($data, 201);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
    }

    public function update($id, $data)
    {
        $params = $this->params()->fromRoute();
        $this->log->info("Update Prehire with id $id and Data - " . json_encode($params, true));
        try {
            $this->prehireService->updateRequest($id, $data);
            return $this->getSuccessResponseWithData($data, 200);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
    }


    public function delete($id)
    {
        $this->log->info("Delete Prehire with id $id");
        try {
            $this->prehireService->deleteRequest($id);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    public function get($id)
    {
        try {
            $result = $this->prehireService->getPrehireRequestData($id);
            return $this->getSuccessResponseWithData($result);
        }
        catch(Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }


}
