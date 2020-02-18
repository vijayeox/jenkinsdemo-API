<?php
/**
 * ServiceTask Callback Api
 */
namespace Oxzion\Service;

use Oxzion\Messaging\MessageProducer;
use Oxzion\Utils\RestClient;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Oxzion\ServiceException;
use Oxzion\Model\JobTable;
use Oxzion\Model\Job;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class JobService extends AbstractService
{

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;

    }

    public function __construct($config, $dbAdapter, MessageProducer $messageProducer, JobTable $table)
    {
        $this->messageProducer = $messageProducer;
        parent::__construct($config, $dbAdapter);
        $this->restClient = new RestClient($this->config['job']['jobUrl'], array());
        $this->table = $table;
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function scheduleNewJob($jobName, $jobGroup, $jobPayload, $cron, $appId, $orgId = null)
    {
        $this->logger->info('EXECUTING SCHEDULE NEW JOB');
        if((!isset($jobName)) || (!isset($jobGroup)) || (!isset($jobPayload)) || (!isset($cron))){
            $this->logger->info("Job Name/Group/Payload/Cron Not Specified");
            throw new EntityNotFoundException("JobName or JobGroup or JobPayload or Cron Expression Not Specified");            
        }
        if(!isset($appId)){
            $this->logger->info("App Id not specified");
            throw new ServiceException("appId not specified", "appid.not.specified");            
        }
        $url = 'setupjob';
        $response = $this->restClient->postWithHeader($url, $jobPayload);
        $this->logger->info("Response - " . print_r($response, true));
        $this->logger->info('SCHEDULE NEW JOB jobpayload is : '. json_encode($jobPayload));
        //check for status and http respnce code
        if (isset($response['body'])) {
            $response = json_decode($response['body'], true);
            $form = new Job();
            $jobData['name'] = $jobName;
            $jobData['job_id'] = $response['JobId'];
            $jobData['org_id'] = isset($orgId) ? $orgId : AuthContext::get(AuthConstants::ORG_ID);
            $jobData['app_id'] = $appId;
            $jobData['group_name'] = $jobGroup;
            $jobData['config'] = json_encode($jobPayload);
            $form->exchangeArray($jobData);
            $form->validate();
            $count = 0;
            $this->beginTransaction();
            try {
                $count = $this->table->save($form);
                if ($count == 0) {
                    $this->rollback();
                    throw new ServiceException("Job Save Failed", "job.save.failed");
                }
                if (!isset($jobData['id'])) {
                    $id = $this->table->getLastInsertValue();
                    $jobData['id'] = $id;
                }
                $this->commit();
            } 
            catch (Exception $e) {
                $this->rollback();
                $this->logger->error($e->getMessage(), $e);
                throw $e;
            }
        }
        $this->logger->info("Schedule JOB DATA in job service- " . print_r($jobData, true));
        return $response;
    }
    
    public function cancelJob($jobName, $jobGroup, $appId)
    {
        $this->logger->info("EXECUTING CANCEL JOB WITH JOB NAME AND GROUP AS PARAMETERS");
        if(!isset($jobName) && !isset($jobName)){
            $this->logger->info('Job Name/Group not specified');
            throw new ServiceException("Job name/group not specified", "jobname.or.jobgroup not specified");
        }
        $query = 'select job_id from ox_job where name = :jobName and group_name = :jobgroup and app_id = :appId';
        $params = array('jobName' => $jobName, 'jobGroup' => $jobGroup, 'appId' => $appId);
        $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
        // exception for jobid
        if(!isset($result)){
            $this->logger->info("Job Id does not exist or not found.");
            throw new ServiceException("Job does not exist", 'job.does.not.exist');            
        }
        $this->logger->info("The job id from ox_job is : " . print_r($result, true));
        $jobId = $result['job_id'];
        $response = $this->cancelJobId($jobId, $appId);
        $this->logger->info("Response - " . print_r($response, true));
        return $response;        
    }

    public function cancelJobId($jobId, $appId, $groupName =null)
    {
        $this->logger->info("EXECUTING CANCEL JOB WITH JOB ID AS PARAMETER");
        if(!isset($jobId) || !isset($appId)){
            $this->logger->info('Job Id or App Id not specified');
            throw new ServiceException("Job Id / App Id not specified", 'jobid.or.appid.not.specified');
        }
        try{
            if(!isset($groupName)){
                $query = 'SELECT group_name from ox_job where job_id = :jobId';
                $params = array('jobId' => $jobId, 'appId' => $appId);
                $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
                $this->logger->info("the response from job table is : $result");
                $groupName = $result['group_name'];
            }
            if(isset($jobId)){
                $query = 'select * from ox_job where job_id = :jobId and app_id = :appId';
                $params = array('jobId' => $jobId, 'appId' => $appId);
            }
            $url = 'canceljob';
            $jobPayload = array('jobid' => $jobId, 'jobgroup' => $groupName);         
            $response = $this->restClient->postWithHeader($url, $jobPayload);
            $this->logger->info("Response - " . print_r($response, true)); 
            //check response json 
        }
        catch (Exception $e){
            $this->logger->info("cancel job failed");
        }
        $this->beginTransaction();
        $count = 0;
        try {
            $query = 'Delete from ox_job where job_id = :jobId';
            $params = array('jobId' => $jobId);
            $result = $this->executeQuerywithBindParameters($query, $params);
            $count = 1;
            $this->logger->info("job successfully deleted......");
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Deletion of job table record was not successful", 'delete.jobtable.record.not.successful');                
            }
            $this->commit();
        } 
        catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }    
        return $response;
    }

    public function getJobDetails($jobId, $appId)
    {
        $this->logger->info("EXECUTING GET JOB ID DETAILS ");
        $query = "SELECT * from ox_job where job_id = :jobId and app_id = :appId";
        $params = array('jobId' => $jobId, 'appId' => $appId);
        $this->logger->info("Job Service - GetJobDetails query - $query");
        $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
        $this->logger->info("The result is - ", print_r($result, true));
        return $result;
    }

    public function getJobsList($appId)
    {
        $this->logger->info("EXECUTING GET JOB DETAILS FOR JOB ID");
        if(!isset($appId)){
            throw new ServiceException("app id not specified",'appid.not.specified');
            
        }
        $this->logger->info("EXECUTING GET JOBS LIST");
        $query = 'select * from ox_job where app_id = :appId';
        $params = array('appId' => $appId);
        $result = $this->executeQuerywithBindParameters($query)->toArray();
        $this->logger->info("The result is - ", print_r($result, true));
        if(isset($result)){
            return $result;
        }
        else{
            throw new Exception("No records found", "no.records.found");
            
        }
        
    }
}