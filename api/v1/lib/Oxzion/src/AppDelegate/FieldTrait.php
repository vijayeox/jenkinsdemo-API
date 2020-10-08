<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\FieldService;

trait FieldTrait
{
    private $fieldService;
    
    public function setFieldService(FieldService $fieldService){
        $this->fieldService = $fieldService;
    }

    protected function getFieldByName($entityId, $fieldName){
        return $this->fieldService->getFieldByName($entityId, $fieldName);
    }
}
