<?php
namespace ProspectResearch\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\ProspectResearch\InfoEngine;

class ProspectResearchController extends AbstractApiController
{
    private $dbAdapter;
    private $infoEngine;
    /**
     * @ignore __construct
     */
    public function __construct(InfoEngine $infoEngine)
    {
        parent::__construct(null, $log, __CLASS__, null);
        $this->infoEngine = $infoEngine;
    }

    public function create($data)
    {
        try {
            $result = $this->infoEngine->GetCompanyInfo($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData(array("result" => $result), 201);
    }
}
