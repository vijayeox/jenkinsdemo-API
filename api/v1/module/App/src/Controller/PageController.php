<?php
namespace App\Controller;

/**
* Page Api
*/
use Zend\Log\Logger;
use App\Model\Page;
use App\Model\PageTable;
use App\Service\PageService;
use App\Service\PageContentService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;

class PageController extends AbstractApiController
{
    private $pageService;
    private $pageContentService;
    /**
    * @ignore __construct
    */
    public function __construct(PageTable $table, PageService $pageService, PageContentService $pageContentService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, Page::class);
        $this->setIdentifierName('pageId');
        $this->pageService = $pageService;
        $this->pageContentService = $pageContentService;
    }
    /**
    * Create Page API
    * @api
    * @link /app/appId/page
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from Page
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Page.
    */
    public function create($data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->pageService->savePage($appId, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
    
    /**
    * GET List Pages API
    * @api
    * @link /app/appId/page
    * @method GET
    * @return array Returns a JSON Response list of Pages based on Access.
    */
    public function getList()
    {
        $appId = $this->params()->fromRoute()['appId'];
        $result = $this->pageService->getPages($appId);
        return $this->getSuccessResponseWithData($result);
    }
    /**
    * Update Page API
    * @api
    * @link /app/appId/page[/:id]
    * @method PUT
    * @param array $id ID of Page to update
    * @param array $data
    * @return array Returns a JSON Response with Status Code and Created Page.
    */
    public function update($id, $data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->pageService->updatePage($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }
    /**
    * Delete Page API
    * @api
    * @link /app/appId/page[/:id]
    * @method DELETE
    * @param $id ID of Page to Delete
    * @return array success|failure response
    */
    public function delete($id)
    {
        $appId = $this->params()->fromRoute()['appId'];
        $response = $this->pageService->deletePage($appId, $id);
        if ($response == 0) {
            return $this->getErrorResponse("Page not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET Page API
    * @api
    * @link /app/appId/page[/:id]
    * @method GET
    * @param $id ID of Page
    * @return array $data
    * @return array Returns a JSON Response with Status Code and Created Page.
    */
    public function get($pageId)
    {
        $appId = $this->params()->fromRoute()['appId'];
        $result = $this->pageContentService->getPageContent($appId, $pageId);
        if ($result == 0) {
            return $this->getErrorResponse("Page not found", 404, ['id' => $pageId]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}
