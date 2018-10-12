<?php

namespace Bookmark\Controller;

use Zend\Log\Logger;
use Bookmark\Model\BookmarkTable;
use Bookmark\Model\Bookmark;
use Bookmark\Service\BookmarkService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;

class BookmarkController extends AbstractApiController {

    private $bookmarkService;
    public function __construct(BookmarkTable $table, BookmarkService $bookmarkService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Bookmark::class);
        $this->setIdentifierName('bookmarkId');
        $this->bookmarkService = $bookmarkService;
    }

    public function create($data){
        try{
            $count = $this->bookmarkService->createBookmark($data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }

    public function getList() {
        $result = $this->bookmarkService->getBookmarks();
        return $this->getSuccessResponseWithData($result);
    }
    public function update($id, $data){
        try{
            $count = $this->bookmarkService->updateBookmark($id,$data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }
    public function delete($id){
        $response = $this->bookmarkService->deleteBookmark($id);
        if($response == 0){
            return $this->getErrorResponse("Announcement not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

}