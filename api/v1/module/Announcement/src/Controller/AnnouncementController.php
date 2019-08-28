<?php
/**
 * Announcement Api
 */

namespace Announcement\Controller;

use Zend\Log\Logger;
use Announcement\Model\AnnouncementTable;
use Announcement\Model\Announcement;
use Announcement\Service\AnnouncementService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
use Oxzion\AccessDeniedException;
use Oxzion\ServiceException;


/**
 * Announcement Controller
 */
class AnnouncementController extends AbstractApiController
{
    /**
    * @var AnnouncementService Instance of Announcement Service
    */
    private $announcementService;
    /**
    * @ignore __construct
    */
    public function __construct(AnnouncementTable $table, AnnouncementService $announcementService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, Announcement::class);
        $this->setIdentifierName('announcementId');
        $this->announcementService = $announcementService;
    }
    /**
    * Create Announcement API
    * @api
    * @link /announcement
    * @method POST
    * @param array $data Array of elements as shown</br>
    * <code> name : string,
    *        status : string,
    *        description : string,
    *        start_date : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *        end_date : dateTime (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *        media_type : string,
    *        media_location : string,
    *        groups : [{'id' : integer}.....multiple*],
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Announcement.</br>
    * <code> status : "success|error",
    *        data : array Created Announcement Object
    * </code>
    */
    public function create($data) {
        $params=$this->params()->fromRoute();
       
        try{
            $count = $this->announcementService->createAnnouncement($data,$params);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(ServiceException $e){
            return $this->getErrorResponse($e->getMessage(),404);
        }
        return $this->getSuccessResponseWithData($data,201);
    }
    /**
    * GET List Announcement API
    * @api
    * @link /announcement
    * @method GET
    * @return array $dataget list of Announcements by User
    * <code>
    * {
    *  string name,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location,
    *  groups : [{'id' : integer}.....multiple]
    * }
    * </code>
    */
    public function getList()
    {
        $params = $this->params()->fromRoute();
        try{
             $result = $this->announcementService->getAnnouncements($params);
        }
        catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        }
        return $this->getSuccessResponseWithData($result);
    }
    /**
    * Update Announcement API
    * @api
    * @link /announcement[/:announcementId]
    * @method PUT
    * @param array $id ID of Announcement to update
    * @param array $data
    * <code>
    * {
    *  integer id,
    *  string name,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location,
    *  groups : [{'id' : integer}.....multiple]
    * }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Announcement.
    */
    public function update($id, $data) {
        try{
            $params = $this->params()->fromRoute();
            $orgId = isset($params['orgId']) ? $params['orgId'] : NULL; 
            $count = $this->announcementService->updateAnnouncement($id,$data,$orgId);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
         catch(ServiceException $e){
            return $this->getErrorResponse($e->getMessage(),404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }
    /**
    * Delete Announcement API
    * @api
    * @link /announcement[/:announcementId]
    * @method DELETE
    * @param $id ID of Announcement to Delete
    * @return array success|failure response
    */
    public function delete($id) {
        try{
            $params = $this->params()->fromRoute();
            $response = $this->announcementService->deleteAnnouncement($id,$params);
        }
        catch(ServiceException $e){
            return $this->getErrorResponse($e->getMessage(),404);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET Announcement API
    * @api
    * @link /announcement[/:announcementId]
    * @method GET
    * @param $id ID of Announcement to Delete
    * @return array $data
    * <code>
    * {
    *  integer id,
    *  string name,
    *  integer org_id,
    *  string status,
    *  string description,
    *  dateTime start_date (ISO8601 format yyyy-mm-ddThh:mm:ss),
    *  dateTime end_date (ISO8601 format yyyy-mm-ddThh:mm:ss)
    *  string media_type,
    *  string media_location
    * }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Announcement.
    */
    public function get($id)
    {
        $params = $this->params()->fromRoute();
        try{
            $result = $this->announcementService->getAnnouncement($id,$params);
        }
        catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        }
        return $this->getSuccessResponseWithData($result);
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
        try{
             $result = $this->announcementService->getAnnouncementsList($filterParams,$params); 
        }
        catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }

    public function announcementToGroupAction()
    {
        $params = $this->params()->fromRoute();
        $data = $this->extractPostData();
        try{
            $count = $this->announcementService->saveGroup($params,$data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        catch(ServiceException $e){
            return $this->getErrorResponse($e->getMessage(),404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    public function announcementGroupsAction()
    {
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery(); // empty method call
        try {
            $count = $this->announcementService->getAnnouncementGroupList($params, $filterParams);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            return $this->getErrorResponse($e->getMessage(), 403);
        }
        return $this->getSuccessResponseDataWithPagination($count['data'], $count['total']);
    }
}