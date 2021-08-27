<?php
namespace App\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Job;
use Oxzion\Model\JobTable;
use Oxzion\Service\JobService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Exception;

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
     * @param Job Name, Job Team, Job Payload(job->url, data, schedule ->cron), CRON, App Id, Account Id(optional)
     * @return array Returns a JSON Response with Created Job details(ID).
     */
    public function scheduleJobAction()
    {
        $params = $this->extractPostData();
        $jobName = $params['jobName'];
        $jobTeam = $params['jobTeam'];
        $jobPayload = $params['jobPayload'];
        $cron = $params['cron'];
        $accountId = isset($params['accountId']) ? $params['accountId'] : AuthContext::get(AuthConstants::ACCOUNT_ID);
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $response = $this->jobService->scheduleNewJob($jobName, $jobTeam, $jobPayload, $cron, $appId, $accountId);
            if ($response && is_array($response)) {
                $this->log->info(":Workflow Step Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Delete Job based on Name and Team API
     * @api
     * @link /app/appId/
     * @method DELETE
     * @param $jobName, $jobTeam Name and Team of Job to delete
     * @return array success|failure response
     */
    public function cancelJobAction()
    {
        $params = $this->extractPostData();
        $jobName = $params['jobName'];
        $jobTeam = $params['jobTeam'];
        $data['app_id'] = $this->params()->fromRoute()['appId'];
        $appId = isset($data['app_id']) ? $data['app_id'] : null;
        $accountId = isset($params['accountId']) ? $params['accountId'] : null;
        try {
            $response = $this->jobService->cancelJob($jobName, $jobTeam, $appId, $accountId);
            if ($response && is_array($response)) {
                $this->log->info(":Workflow Step Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
            $this->jobService->cancelJobId($jobId, $appId);
            return $this->getSuccessResponse();
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }
}
