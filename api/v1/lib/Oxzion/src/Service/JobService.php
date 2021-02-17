<?php
/**
 * ServiceTask Callback Api
 */
namespace Oxzion\Service;

use Exception;
use Oxzion\ValidationException;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\EntityNotFoundException;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Model\Job;
use Oxzion\Model\JobTable;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Utils\RestClient;

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

    // job name is file ID 
    // send appId in the parameter as Uuid
    public function scheduleNewJob($jobName, $jobGroup, $jobPayload, $cron, $appId, $accountId = null)
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
        $appNewId = $this->getIdFromUuid('ox_app', $appId);
        if($appNewId != 0){
            $appId = $appNewId;
        }
        $query = "SELECT * from ox_job where name = :name and app_id = :appId";
        $params = array('name' => $jobName, 'appId' => $appId);
        if ($accountId) {            
            $accountId = !is_numeric($accountId) ? $this->getIdFromUuid('ox_account', $accountId) : $accountId;
            $query .= " AND account_id =:accountId";
            $params['accountId'] = $accountId;
        }
        $result = $this->executeQuerywithBindParameters($query, $params)->toArray(); 
        if($accountId && !empty($result)){
            $config = !is_array($result[0]['config']) ? json_decode($result[0]['config'],true) : $result[0]['config'];
            $jobPayload['cron'] = $config['schedule']['cron'];
            $this->cancelJobInternal($result[0]['job_id'], $jobGroup);
            unset($result);
        }
        $jobData['name'] = $jobName;
        $jobData['account_id'] = $accountId;
        $jobData['app_id'] = $appId;
        $jobData['group_name'] = $jobGroup;
        $jobData['config'] = json_encode($jobPayload);
        if($accountId){
            $url = 'setupjob';
            $responseReturn = $this->restClient->postWithHeader($url, $jobPayload);

            if (!isset($responseReturn['body'])) {
                throw new ServiceException("Schedule Job Error", 'schedule.job.exception');            
            }
            $response = json_decode($responseReturn['body'], true);
            if($response['Success'] != true){
                throw new ServiceException("Schedule Job not successful", 'schedule.job.not.successful');
            }
            $jobData['job_id'] = $response['JobId']; // make nullable
        }
        $this->logger->info("adding job details to ox_job table with the following details");
        $form = new Job($this->table);
        if(isset($result) && !$accountId && !empty($result)){
            $form->loadById($result[0]['id']);
        }
        $form->assign($jobData);
        $this->logger->info($form);
        try {
            $this->beginTransaction();
            $form->save();
            $id = $form->getGenerated(true);
            $jobData['id'] = $id['id'];
            $this->commit();
        } 
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $this->logger->info("Schedule JOB DATA in job service- " . print_r($jobData, true));
        return $jobData;
    }
    
    public function cancelJob($jobName, $jobGroup, $appId, $accountId = null)
    {
        $this->logger->info("EXECUTING CANCEL JOB WITH JOB NAME AND GROUP AS PARAMETERS");
        if(!isset($jobName) && !isset($jobName)){
            $this->logger->info('Job Name/Group not specified');
            return;
        }
        $appNewId = $this->getIdFromUuid('ox_app', $appId);
        if($appNewId != 0){
            $appId = $appNewId;
        }
        $query = 'SELECT * from ox_job where name = :jobName and group_name = :jobGroup and app_id = :appId';
        $params = array('jobName' => $jobName, 'jobGroup' => $jobGroup, 'appId' => $appId);
        if($accountId){
            $accountId = !is_numeric($accountId) ? $this->getIdFromUuid('ox_account', $accountId) : $accountId;
            $query .= " AND account_id =:accountId ";
            $params['accountId'] = $accountId;
        }else{
            $query .= " AND account_id IS NULL ";
        }
        $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
        if(!isset($result) || empty($result)){
            $this->logger->info("Job Id does not exist or not found.");
            throw new ServiceException('No record found', 'no.record.found', OxServiceException::ERR_CODE_NOT_FOUND);            
        }
        $this->logger->info("The job id from ox_job is : " . print_r($result, true));
        $jobId = $result[0]['job_id'];
        $this->cancelJobInternal($jobId, $jobGroup);
    }

    public function cancelAppJobs($appId){
        $this->logger->info("In Cancel App Jobs");
        try{
            $jobList = $this->getJobsList($appId);
            foreach ($jobList as $key => $value) {
                $this->cancelJob($value['name'], $value['group_name'], $appId);
            }
        }catch(Exception $e){
            $this->logger->info("cancel App Jobs failed".$e->getMessage());
            throw $e;
        }
        
    }

    public function cancelJobId($jobId, $appId, $groupName =null)
    {
        $this->logger->info("EXECUTING CANCEL JOB WITH JOB ID AS PARAMETER");        
        if(!isset($jobId) || !isset($appId)){
            $this->logger->info('Job Id or App Id not specified');
            throw new ServiceException("Job Id / App Id not specified", 'jobid.or.appid.not.specified', OxServiceException::ERR_CODE_NOT_ACCEPTABLE);
        }
        $appNewId = $this->getIdFromUuid('ox_app', $appId);
        if($appNewId != 0){
            $appId = $appNewId;
        }
        $this->logger->info("appId is : ".print_r($appId,true));
        if(isset($jobId)){
            $query = 'SELECT * from ox_job where job_id = :jobId and app_id = :appId';
            $params = array('jobId' => $jobId, 'appId' => $appId);
            $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
            if(empty($result)){
                //as no job is found we just return
                return;
            }
        }
        if(!isset($groupName)){
            $query = 'SELECT group_name from ox_job where job_id = :jobId and app_id = :appId';
            $params = array('jobId' => $jobId, 'appId' => $appId);
            $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
            $this->logger->info("the response from job table is : ".print_r($result, true));
        }
        $this->cancelJobInternal($jobId, $groupName);
    }

    private function cancelJobInternal($jobId, $groupName){
        $url = 'canceljob';            
        $jobPayload = array('jobid' => $jobId, 'jobgroup' => $groupName);
        $response = $this->restClient->postWithHeader($url, $jobPayload);
        $this->logger->info("Response - " . print_r($response, true));
        if (!isset($response) && !isset($response['body'])) {
            throw new ServiceException("Schedule Job Error", 'schedule.job.exception');            
        }
    
        $count = 0;
        try {
            $this->beginTransaction();
            $query = 'DELETE from ox_job where job_id = :jobId';
            $params = array('jobId' => $jobId);
            $result = $this->executeUpdateWithBindParameters($query, $params);
            $count = 1;
            $this->logger->info("job successfully deleted......");
            if ($count == 0) {
                throw new ServiceException("Deletion of job table record was not successful", 'delete.jobtable.record.not.successful');                
            }
            $this->commit();
        } 
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }    
    }

    public function getJobDetails($jobId, $appId)
    {        
        $appId = $this->getIdFromUuid('ox_app', $appId);
        $this->logger->info("EXECUTING GET JOB ID DETAILS ");
        $query = "SELECT * from ox_job where job_id = :jobId and app_id = :appId";
        $params = array('jobId' => $jobId, 'appId' => $appId);
        $this->logger->info("Job Service - GetJobDetails query - $query");
        $result = $this->executeQuerywithBindParameters($query, $params)->toArray();  
        if(empty($result)){
            throw new ServiceException("No records found", "no.records.found", OxServiceException::ERR_CODE_NOT_FOUND);
        }
        $this->logger->info("The result is - ", print_r($result, true));
        return $result;
    }

    public function getJobsList($appId)
    {
        $this->logger->info("EXECUTING GET JOB DETAILS FOR JOB ID");
        try{
            $appId = $this->getIdFromUuid('ox_app', $appId);
            if(!isset($appId)){
                throw new ServiceException("app id not specified",'appid.not.specified');            
            }
            $this->logger->info("EXECUTING GET JOBS LIST");
            $query = 'SELECT * from ox_job where app_id = :appId';
            $params = array('appId' => $appId);
            $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
            $this->logger->info("The result is - ", print_r($result, true));
            return $result;
        }
        catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }
}