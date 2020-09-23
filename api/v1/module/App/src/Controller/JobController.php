<?php
namespace App\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\EntityNotFoundException;
use Oxzion\Model\Job;
use Oxzion\Model\JobTable;
use Oxzion\ServiceException;
use Oxzion\Service\JobService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class JobController extends AbstractApiController
{
    private $jobService;
    /**
     * @ignore __construct
     */
    public function __construct(JobTable $table, JobService $jobService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Job::class);
        $this->setIdentifierName('appId');
        $this->jobService = $jobService;
    }
    /**
     * Create job API
     * @api
     * @link /app/appId/schedule
     * @method POST
     * @param Job Name, Job Group, Job Payload(job->url, data, schedule ->cron), CRON, App Id, Account Id(optional)
     * @return array Returns a JSON Response with Created Job details(ID).
     */
    public function scheduleJobAction()
    {
        $params = $this->extractPostData();
        $jobName = $params['jobName'];
        $jobGroup = $params['jobGroup'];
        $jobPayload = $params['jobPayload'];
        $cron = $params['cron'];
        $accountId = isset($params['accountId']) ? $params['accountId'] : AuthContext::get(AuthConstants::ACCOUNT_ID);
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $response = $this->jobService->scheduleNewJob($jobName, $jobGroup, $jobPayload, $cron, $appId, $accountId);
            if ($response && is_array($response)) {
                $this->log->info(":Workflow Step Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (EntityNotFoundException $e) {
            return $this->getErrorResponse($e->getMessage());
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 406);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * GET Jobs List API
     * @api
     * @link /app/appId/schedule
     * @method GET
     * @return array Returns a JSON Response list of Files based on Access.
     */
    public function getJobsListAction()
    {
        $params = $this->extractPostData();
        $data['app_id'] = $this->params()->fromRoute()['appId'];
        $appId = isset($data['app_id']) ? $data['app_id'] : null;
        try {
            $response = $this->jobService->getJobsList($appId);
            if ($response && is_array($response)) {
                $this->log->info(":Workflow Step Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (ValidationException $e) {
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 406);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * GET Job API
     * @api
     * @link /app/appId/form[/:id]
     * @method GET
     * @param $id ID of Job
     * @return array Returns a JSON Response with Status Code and Created Job.
     */
    public function getJobDetailsAction()
    {
        $appId = $this->params()->fromRoute()['appId'];
        $jobId = $this->params()->fromRoute()['jobId'];
        try {
            $response = $this->jobService->getJobDetails($jobId, $appId);
            if ($response && is_array($response)) {
                $this->log->info("Job Service successfully executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (ValidationException $e) {
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 406);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete Job based on Name and Group API
     * @api
     * @link /app/appId/
     * @method DELETE
     * @param $jobName, $jobGroup Name and Group of Job to delete
     * @return array success|failure response
     */
    public function cancelJobAction()
    {
        $params = $this->extractPostData();
        $jobName = $params['jobName'];
        $jobGroup = $params['jobGroup'];
        $data['app_id'] = $this->params()->fromRoute()['appId'];
        $appId = isset($data['app_id']) ? $data['app_id'] : null;
        try {
            $response = $this->jobService->cancelJob($jobName, $jobGroup, $appId);
            if ($response && is_array($response)) {
                $this->log->info(":Workflow Step Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (ValidationException $e) {
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 406);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete Job ID API
     * @api
     * @link /app/appId/
     * @method DELETE
     * @param $jobId ID of Job to delete
     * @return array success|failure response
     */
    public function cancelJobIdAction()
    {
        $params = $this->extractPostData();
        $jobId = $params['jobId'];
        $data['app_id'] = $this->params()->fromRoute()['appId'];
        $appId = isset($data['app_id']) ? $data['app_id'] : null;
        try {
            $response = $this->jobService->cancelJobId($jobId, $appId);
            if ($response && is_array($response)) {
                $this->log->info("Job Cancellation Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (ValidationException $e) {
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 406);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 500);
        }
    }
}
