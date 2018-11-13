<?php
namespace Widget\Service;

use Oxzion\Service\AbstractService;
use Widget\Model\WidgetTable;
use Widget\Model\Widget;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;

class WidgetService extends AbstractService{
    private $table;

    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, WidgetTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    /**
    * GET Widgets Service
    * @method  getWidgets get List of Widgets by Organization
    * @return array $dataget list of Widgets by User
    * <code>
    * {
    * }
    * </code>
    */
    public function getWidgets() { 
            $sql = $this->getSqlObject();
            $select = $sql->select();
            $select->from('ox_widget')
                    ->columns(array("*"))
                    ->join('ox_org_widget', 'ox_widget.id = ox_org_widget.widgetid', array(),'left')
                    ->where(array('ox_org_widget.orgid' => AuthContext::get(AuthConstants::ORG_ID))); //TODO if admin, then do not apply filter. 
            return $this->executeQuery($select)->toArray();
    }
}
?>