<?php
namespace Screen\Controller;

use Zend\Log\Logger;
use Screen\Model\Screen;
use Screen\Model\ScreenTable;
use Screen\Service\ScreenService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class ScreenController extends AbstractApiController
{
    private $dbAdapter;
    private $screenService;

    /**
    * @ignore __construct
    */
    public function __construct(ScreenTable $table, ScreenService $screenService, Logger $log)
    {
        parent::__construct($table, $log, __CLASS__, Screen::class);
        $this->screenService = $screenService;
        $this->setIdentifierName('id');
    }
}
