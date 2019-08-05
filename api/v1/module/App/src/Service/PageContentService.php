<?php
namespace App\Service;

use App\Model\PageContentTable;
use App\Model\PageContent;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\UuidUtil;
use Exception;

class PageContentService extends AbstractService
{
    public function __construct($config, $dbAdapter, PageContentTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function getPageContent($pageId)
    {
        $queryString = " SELECT ox_app_menu.icon,ox_app_menu.name,ox_app_menu.page_id,ox_app_menu.parent_id,ox_app_menu.sequence,ox_app_menu.uuid from ox_app_menu where group_id=0 union select ox_app_menu.icon,ox_app_menu.name,ox_app_menu.page_id,ox_app_menu.parent_id,ox_app_menu.sequence,ox_app_menu.uuid from ox_app_menu LEFT JOIN ox_user_group on ox_user_group.group_id=ox_app_menu.group_id where ox_user_group.avatar_id = ".$userId;
        $resultSet = $this->executeQuerywithParams($queryString)->toArray();

        if (count($resultSet)==0) {
            return 0;
        }
        
        return $response[0];
    }
}
