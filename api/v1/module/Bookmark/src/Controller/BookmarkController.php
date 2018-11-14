<?php
/**
* Bookmark Api
*/
namespace Bookmark\Controller;

use Zend\Log\Logger;
use Bookmark\Model\BookmarkTable;
use Bookmark\Model\Bookmark;
use Bookmark\Service\BookmarkService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;

class BookmarkController extends AbstractApiController {
    /**
    * @ignore bookmarkService
    */
    private $bookmarkService;
    /**
    * @ignore __construct
    */
    public function __construct(BookmarkTable $table, BookmarkService $bookmarkService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Bookmark::class);
        $this->setIdentifierName('bookmarkId');
        $this->bookmarkService = $bookmarkService;
    }

    /**
    * Create Bookmark API
    * @api
    * @method POST
    * @link /bookmark
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               url : string,
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Bookmark.
    */
    public function create($data){
        try{
            $count = $this->bookmarkService->createBookmark($data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new Bookmark", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }

    /**
    * GET List Bookmark API
    * @api
    * @method GET
    * @return array Returns a JSON Response with Array of Bookmarks
    */
    public function getList() {
        $result = $this->bookmarkService->getBookmarks();
        return $this->getSuccessResponseWithData($result);
    }
    /**
    * Update Bookmark API
    * @api
    * @method PUT
    * @link /bookmark[/:bookmarkId]
    * @param array $id ID of Bookmark to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created Bookmark.
    */
    public function update($id, $data){
        try{
            $count = $this->bookmarkService->updateBookmark($id,$data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getErrorResponse("Bookmark not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }
    /**
    * Delete Bookmark API
    * @api
    * @method DELETE
    * @link /bookmark[/:bookmarkId]
    * @param $id ID of Bookmark to Delete
    * @return array success|failure response
    */
    public function delete($id){
        $response = $this->bookmarkService->deleteBookmark($id);
        if($response == 0){
            return $this->getErrorResponse("Announcement not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

}