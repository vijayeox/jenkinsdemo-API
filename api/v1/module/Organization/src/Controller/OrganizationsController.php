<?php
namespace Organization\Controller;

use Zend\Log\Logger;
use Organization\Model\Organizations;
use Organization\Model\OrganizationsTable;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Entity\Organization;
use Oxzion\Model\Table\OrganizationTable;

class OrganizationsController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new Organization());
        $this->setIdentifierName('organizationId');
    }
}