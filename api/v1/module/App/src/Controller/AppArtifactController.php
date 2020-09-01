<?php

namespace App\Controller;

use App\Service\AppArtifactService;
use Exception;
use Oxzion\Controller\AbstractApiController;


class AppArtifactController extends AbstractApiController {
    private $appArtifactService = NULL;

    public function __construct(AppArtifactService $appArtifactService) {
        $this->appArtifactService = $appArtifactService;
        $this->log = $this->getLogger();
    }

    public function addArtifactAction() {
        $routeParams = $this->params()->fromRoute();
        $appId = $routeParams['appId'];
        $artifactType = $routeParams['artifactType'];
        try {
            $this->appArtifactService->saveArtifact($appId, $artifactType);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    public function deleteArtifactAction() {
        $routeParams = $this->params()->fromRoute();
        $appId = $routeParams['appId'];
        $artifactType = $routeParams['artifactType'];
        $artifactName = $routeParams['artifactName'];
        try {
            $this->appArtifactService->deleteArtifact($appId, $artifactType, $artifactName);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }
}
