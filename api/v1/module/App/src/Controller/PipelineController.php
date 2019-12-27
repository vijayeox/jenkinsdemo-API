<?php
namespace App\Controller;

/**
* Pipleline Api
*/
use Oxzion\Service\CommandService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Zend\Http\Request as HttpRequest;

class PipelineController extends AbstractApiController
{
    private $commandService;
    protected $log;
    /**
    * @ignore __construct
    */
    public function __construct(CommandService $commandService)
    {
        $this->commandService = $commandService;
        $this->log = $this->getLogger();
    }
    public function executePipelineAction(){
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $appUuid = $this->params()->fromRoute()['appId'];
        unset($params['method']);
        unset($params['controller']);
        unset($params['action']);
        unset($params['access']);
        try {
            $response = $this->commandService->runCommand($params,$this->getRequest());
            if ($response && is_array($response)) {
                $this->log->info(":Pipleline Service Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (ValidationException $e) {
            $this->log->error(":Exception while Performing Service Task-" . $e->getMessage(), $e);
            $response = ['data' => $params, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        } catch (EntityNotFoundException $e) {
            $this->log->info(":Entity Not found -" . $e->getMessage());
            $response = ['data' => $params];
            return $this->getErrorResponse($e->getMessage(), 404, $response);
        } catch (Exception $e) {
            $this->log->error(":Error -" . $e->getMessage(), $e);
            $response = ['data' => $params];
            return $this->getErrorResponse($e->getMessage(), 500, $response);
        }
    }
}