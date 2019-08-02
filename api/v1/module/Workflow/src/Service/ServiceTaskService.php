<?php
/**
* ServiceTask Callback Api
*/
namespace Workflow\Service;

use Oxzion\Service\AbstractService;
use Workflow\Model\ActivityInstanceTable;
use Oxzion\Model\ActivityInstance;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Service\TemplateService;
use Zend\Log\Logger;
use Exception;

class ServiceTaskService extends AbstractService {
    /**
    * @var ServiceTaskService Instance of Task Service
    */
    private $activityinstanceService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter,Logger $log,TemplateService $templateService) {
        parent::__construct($config, $dbAdapter,$log);
    }

    public function runCommand($data){
        //TODO Execute Service Task Methods
        // if(isset($data['template'])){

        // }
        // print_r('test');exit;
    }
}
?>