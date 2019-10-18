<?php
namespace Widget\Controller;

use Widget\Model\Widget;
use Widget\Model\WidgetTable;
use Widget\Service\WidgetService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class WidgetController extends AbstractApiController
{
    private $dbAdapter;
    private $widgetService;
    /**
    * @ignore __construct
    */
    public function __construct(WidgetTable $table, WidgetService $widgetService)
    {
        parent::__construct($table, Widget::class);
        $this->widgetService=$widgetService;
        $this->setIdentifierName('widgetId');
    }
    /**
    * GET List Widget API
    * @api
    * @link /widget
    * @method GET
    * @return array $dataget list of Widgets by User
    * <code>
    * {
    * }
    * </code>
    */
    public function getList()
    {
        $params = $this->params()->fromRoute();
        $result = $this->widgetService->getWidgets();
        return $this->getSuccessResponseWithData($result);
    }
}
