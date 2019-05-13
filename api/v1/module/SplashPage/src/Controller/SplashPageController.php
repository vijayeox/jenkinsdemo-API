<?php
/**
 * SplashPage Api
 */

namespace SplashPage\Controller;

use Zend\Log\Logger;
use SplashPage\Model\SplashPageTable;
use SplashPage\Model\SplashPage;
use SplashPage\Service\SplashPageService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Bos\ValidationException;
use Zend\InputFilter\Input;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

use Bos\Service\AbstractService;
use Zend\Db\Sql\Expression;
use Exception;
/**
 * SplashPage Controller
 */
class SplashPageController extends AbstractApiController {
    /**
    * @var SplashPageService Instance of SplashPage Service
    */
    private $SplashPageService;
    /**
    * @ignore __construct
    */
    public function __construct(SplashPageTable $table, SplashPageService $SplashPageService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, SplashPage::class);
        $this->setIdentifierName('splashpageId');
        $this->SplashPageService = $SplashPageService;
    }
    /**
    * Create Splash Page API
    * Creates a Splash Page 
    * @api
    * @link /splashpage
    * @method POST
    * @param array $data Array of elements as shown</br>
    * <code> content : string,
    *        org_id : int
    * </code>
    * @return array Returns a JSON Response with Status Code and Created SplashPage.</br>
    * <code> status : "success|error",
    *        data : array Created SplashPage Object
    * </code>
    */
    public function create($data) {
        try{
            $count = $this->SplashPageService->createSplashPage($data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }

    /**
    * Get List SplashPage API
    * Gets the Splash Page for this user
    * @api
    * @link /splashpage
    * @method GET
    * @return array Returns a JSON Response with Status Code and Splash Page.</br>
    * <code> status : "success|error",
    *        data : array Retrieved SplashPage Object
    * </code>
    */
    public function getList() {
        try{
            $result = $this->SplashPageService->getSplashPages();
        }catch(ValidationException $e){
            $response = ['data' => [], 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
    * Get Splash Page for Organization API
    * Get the Splash Page for this organizaionId    
    * @api
    * @link /splashpage/organization/:organizationId
    * @method GET
    * @return array Returns a JSON Response with Status Code and splashpage.</br>
    * <code> status : "success|error",
    *        data : array Retrieved SplashPage Object
    * </code>
    */
    public function getSplashpageforOrganizationAction() {
        $params = $this->params()->fromRoute();
        $organizationId = $params['organizaionId'];
        try{
            $splashpageList = $this->SplashPageService->getSplashpageforOrganizaion($organizationId); 
        }
        catch(ValidationException $e){
            $response = ['data' => [], 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
		return $this->getSuccessResponseWithData($splashpageList);
    }
    
    /**
    * replaceList SplashPage API
    * Updates the content of the splashpage for this org_id. 
    * @api
    * @link /splashpage
    * @method PUT
    * @param array $data 
    * <code>
    * {
    *  string content
    *  int    org_id
    *  int enabled 
    * }
    * </code>
    * @return array Returns a JSON Response with Status Code and Updated SplashPage.
    * <code> status : "success|error",
    *        data : array Updated SplashPage Object
    * </code>
    */
    public function replaceList($data)
    {
        try{
            if (!array_key_exists('org_id', $data)){
                $errors = array();
                $errors['org_id'] = 'required';
                $validationException = new ValidationException();
                $validationException->setErrors($errors);
                throw $validationException;
            }

            $org_id = $data['org_id'];  
            $this->log->info($this->logClass . ": update for id - $org_id ");
            
            //$filter = $this->getParentFilter(); this call causes an error
            $filter = null;

            // query to get the id for the splashpage for this user
            $id = $this->SplashPageService->GetSplashpageId($org_id);
            
            $obj = $this->table->get($id, $filter); 
            $theSplashPage = $obj; //give it a meaningful name

            if (is_null($obj)) {
                return $this->getErrorResponse("Entity not found for id - $id", 404);
            }
        
            // $obj = new $this->modelClass; //don't know why this is needed
            // $obj->exchangeArray($data); 
            // $obj->id = $id; there is no id property to this object!

            // update the existing splashpage with the new data
            $theSplashPage->exchangeArray($data); 
            
            $validationResult = $this->validate($theSplashPage);
            if (!$validationResult->isValid()) {
                $validationException = new ValidationException();
                $errors = ['message' => $validationResult->getMessage() . $org_id . "."];
                $validationException->setErrors($errors);
                throw $validationException;
            }

            //Save changes to the database
            $count = $this->table->save($theSplashPage);
            if ($count == 0) { /* If the data did not change.  Maybe should throw a validataion exception. */
                return $this->getFailureResponse("Failed to update data for id - $id", $data);
            }
        }catch (ValidationException $e){
            $response = ['data' => [], 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData($theSplashPage->toArray(),200);
    }
    
    /**
    * Delete SplashPage API (This is not used because we disable instead of deleting )
    * @api
    * @link /SplashPage[/:SplashPageId]
    * @method DELETE
    * @param $id ID of SplashPage to Delete
    * @return array success|failure response
    */
    public function delete($id) {
        // echo "Hello From delete";
        // $response = $this->SplashPageService->deleteSplashPage($id);
        // if($response == 0) {
        //     return $this->getErrorResponse("SplashPage not found", 404, ['id' => $id]);
        // }
        // return $this->getSuccessResponse();
    }

    
    /**
    * GET SplashPage API
    * @api
    * @link /SplashPage[/:SplashPageId]
    * @method GET
    * @param $id ID of SplashPage to Delete
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
    * @return array Returns a JSON Response with Status Code and Created SplashPage.
    */ 
    public function get($id) {
        // $result = $this->SplashPageService->getSplashPage($id);
        // if($result == 0) {
        //     return $this->getErrorResponse("SplashPage not found", 404, ['id' => $id]);
        // }
        // return $this->getSuccessResponseWithData($result);
    }
    /**
    * GET List of All SplashPage API
    * @api
    * @link /SplashPage
    * @method GET
    * @return array $dataget list of SplashPages
    */
    public function SplashPageListAction() {
        // echo "hello from splashpagelistaction";
        // $result = $this->SplashPageService->getSplashPagesList();
        // return $this->getSuccessResponseWithData($result);
    }

    public function SplashPageToGroupAction(){
    //     $params = $this->params()->fromRoute();
    //     $id=$params['SplashPageId'];
    //     $data = $this->params()->fromPost();
    //     try{
    //         $count = $this->SplashPageService->insertSplashPageForGroup($id,$data);
    //     } catch (ValidationException $e) {
    //         $response = ['data' => $data, 'errors' => $e->getErrors()];
    //         return $this->getErrorResponse("Validation Errors",404, $response);
    //     }
    //     if($count == 0) {
    //         return $this->getErrorResponse("Entity not found", 404);
    //     }
    //     if($count == 2) {
    //         return $this->getErrorResponse("Enter Group Ids", 404);
    //     }
    //     return $this->getSuccessResponseWithData($data,200);
    }

}


