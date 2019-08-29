<?php
namespace App\Controller;

/**
* PageContent Api
*/
use Zend\Log\Logger;
use App\Model\PageContent;
use App\Model\PageContentTable;
use App\Service\PageContentService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;

class PageContentController extends AbstractApiController
{
    private $pageContentService;
    /**
    * @ignore __construct
    */
    public function __construct(PageContentTable $table, PageContentService $pageContentService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, PageContent::class);
        $this->setIdentifierName('pageContentId');
        $this->pageContentService = $pageContentService;
    }
    /**
    * Create PageContent API
    * @api
    * @link /app/appId/pagecontent
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from PageContent
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created PageContent.
    */
    public function create($data)
    {
        try {
            $count = $this->pageContentService->createPageContent($data);
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
    * GET List PageContents API
    * @api
    * @link /app/appId/pagecontent
    * @method GET
    * @return array Returns a JSON Response list of PageContents based on Access.
    */
    public function getList()
    {
        return $this->getInvalidMethod();
    }
    /**
    * Update PageContent API
    * @api
    * @link /app/appId/pagecontent[/:id]
    * @method PUT
    * @param array $id ID of PageContent to update
    * @param array $data
    * @return array Returns a JSON Response with Status Code and Created PageContent.
    */
    public function update($id, $data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->pageContentService->updatePageContent($id, $data);
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
    * Delete PageContent API
    * @api
    * @link /app/appId/pagecontent[/:id]
    * @method DELETE
    * @param $id ID of PageContent to Delete
    * @return array success|failure response
    */
    public function delete($id)
    {
        $response = $this->pageContentService->deletePageContent($id);
        if ($response == 0) {
            return $this->getErrorResponse("PageContent not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET PageContent API
    * @api
    * @link /app/appId/pagecontent[/:id]
    * @method GET
    * @param $id ID of PageContent
    * @return array $data
    * @return array Returns a JSON Response with Status Code and Created PageContent.
    */
    public function get($pageContentId)
    {
        $result = $this->pageContentService->getContent($pageContentId);
        if (empty($result)) {
            return $this->getErrorResponse("Page Content not found", 404, ['id' => $pageContentId]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}
