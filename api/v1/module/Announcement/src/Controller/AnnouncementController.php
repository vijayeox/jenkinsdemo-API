<?php

namespace Announcement\Controller;

use Zend\Log\Logger;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Announcement\Service\AnnouncementService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;

class AnnouncementController extends AbstractApiController {
    private $announcementService;

    public function __construct(AnnouncementTable $table, AnnouncementService $announcementService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Announcement::class);
        $this->setIdentifierName('announcementId');
        $this->announcementService = $announcementService;
    }

    /**
    *   $data should be in the following JSON format
    *   {
    *       'id' : integer,
    *       'name' : string,
    *       'org_id' : integer,
    *       'status' : string,
    *       'description' : string,
    *       'start_date' : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *       'end_date' : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *       'media_type' : string,
    *       'media_location' : string,
    *       'groups' : [
    *                       {'id' : integer}.
    *                       ....multiple 
    *                  ],
    *   }
    *
    *
    */
    public function create($data){
        try{
            $count = $this->announcementService->createAnnouncement($data);
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
        $result = $this->announcementService->getAnnouncements();
        return $this->getSuccessResponseWithData($result);
    }
    public function update($id, $data){
        try{
            $count = $this->announcementService->updateAnnouncement($id,$data);
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
        $response = $this->announcementService->deleteAnnouncement($id);
        if($response == 0){
            return $this->getErrorResponse("Announcement not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }


}
