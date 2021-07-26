<?php
namespace Callback\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Callback\Service\PrehireCallbackService;
use \Exception;

class PrehireCallbackController extends AbstractApiControllerHelper
{
    private $prehireCallbackService;
    private $log;

    /**
     * @ignore __construct
     */
    public function __construct(PrehireCallbackService $prehireCallbackService)
    {
        $this->prehireCallbackService = $prehireCallbackService;
        $this->log = $this->getLogger();
    }

    /**
     * Execute Prehire Action
     * @api
     * @link /callback/prehire/service/:implementation
     * @method POST
     * @return array Returns a Status Code</br>
     * <code> status : "success|error",
     * </code>
     */
    public function executeAction()
    {
        $params = array_merge($this->extractPostData(),$this->params()->fromRoute());
        $this->log->info("Prehire execute Params- " . json_encode($params));
        try {
            $this->prehireCallbackService->invokeImplementation($params);
        } catch(Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->customSuccessResponse(['response' => ['success' => true,'status' => 200]]);
    }
}
