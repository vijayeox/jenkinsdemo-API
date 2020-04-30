<?php
/**
 * Announcement Api
 */

namespace Announcement\Controller;

use Announcement\Service\AnnouncementService;
use Oxzion\AccessDeniedException;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ServiceException;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

/**
 * Announcement Controller
 */
class HomescreenAnnouncementController extends AbstractApiControllerHelper
{
    /**
     * @var AnnouncementService Instance of Announcement Service
     */
    private $announcementService;
    /**
     * @ignore __construct
     */
    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
        $this->log = $this->getLogger();
    }
    
    /**
     * GET List of All Announcement API
     * @api
     * @link /announcement
     * @method GET
     * @return array $dataget list of Announcements
     */
    public function announcementListAction()
    {
        $filterParams = $this->params()->fromQuery();
        $params = $this->params()->fromRoute();
        try {
            $result = $this->announcementService->getHomescreenAnnouncementList($filterParams, $params);
        } catch (AccessDeniedException $e) {
            $this->log->error('\ngetAnnouncementsList AccessDeniedException - ' .$e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (Exception $e) {
            $this->log->error("-> \ngetAnnouncementsList - Exception".$e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

}
