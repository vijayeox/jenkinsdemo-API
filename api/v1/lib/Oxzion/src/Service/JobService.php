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
    public function scheduleNewJob($jobName, $jobGroup, $jobPayload, $cron, $appId, $orgId = null)
    {
        $this->logger->info('EXECUTING SCHEDULE NEW JOB');
        try{
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
            if(!(isset($orgId))){
                $orgId = AuthContext::get(AuthConstants::ORG_ID);
                if(!isset($orgId)){
                    throw new ServiceException('Organization Id not found', 'org.id.not.found');               
                }
            }
            $query = "SELECT job_id from ox_job where group_name = :groupName and name = :name and app_id = :appId";
            $params = array('groupName' => $jobGroup , 'name' => $jobName, 'appId' => $appId );
            $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
            if(!empty($result)){
                throw new ServiceException("Job already exists", 'job.already.exists');                
            }
            $url = 'setupjob';
            $responseReturn = $this->restClient->postWithHeader($url, $jobPayload);
            $this->logger->info("Response - " . print_r($responseReturn, true));

            if (!isset($responseReturn['body'])) {
                throw new ServiceException("Schedule Job Error", 'schedule.job.exception');            
            }
            $response = json_decode($responseReturn['body'], true);
            if($response['Success'] != true){
                throw new ServiceException("Schedule Job not successful", 'schedule.job.not.successful');
            }
        }
		catch (Exception $e) {
            $this->logger->info("Schedule Job Error ---- " . print_r($e->getMessage(), true));
            throw $e;
        }
        if($response['Success'])
        {
            $this->logger->info("adding job details to ox_job table with the following details");
            $form = new Job();
            $jobData['name'] = $jobName;
            $jobData['job_id'] = $response['JobId'];
            $jobData['org_id'] = $orgId;
            $jobData['app_id'] = $appId;
            $jobData['group_name'] = $jobGroup;
            $jobData['config'] = json_encode($jobPayload);
            $form->exchangeArray($jobData);
            $form->validate();
            $this->logger->info($form);
            $count = 0;
            $this->beginTransaction();
            try {
                $count = $this->table->save($form);
                $this->logger->info("the count returned is: ".$count);
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
        return $jobData;
    }
    
    public function cancelJob($jobName, $jobGroup, $appId)
    {
        try
        {
            $this->logger->info("EXECUTING CANCEL JOB WITH JOB NAME AND GROUP AS PARAMETERS");
            if(!isset($jobName) && !isset($jobName)){
                $this->logger->info('Job Name/Group not specified');
                //throw new ServiceException("Job name/group not specified", "jobname.or.jobgroup not specified");
                return;
            }
            $appNewId = $this->getIdFromUuid('ox_app', $appId);
            if($appNewId != 0){
                $appId = $appNewId;
            }
            $query = 'SELECT * from ox_job where name = :jobName and group_name = :jobGroup and app_id = :appId';
            $params = array('jobName' => $jobName, 'jobGroup' => $jobGroup, 'appId' => $appId);
            $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
            if(!isset($result) || empty($result)){
                $this->logger->info("Job Id does not exist or not found.");
                throw new ServiceException('No record found', 'no.record.found');            
            }
            $this->logger->info("The job id from ox_job is : " . print_r($result, true));
            $jobId = $result[0]['job_id'];
            $appId = $this->getUuidFromId('ox_app', $appId);
            $response = $this->cancelJobId($jobId, $appId, $jobGroup);
            $this->logger->info("Response - " . print_r($response, true));
        }
        catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $response;        
    }

    public function cancelJobId($jobId, $appId, $groupName =null)
    {
        $this->logger->info("EXECUTING CANCEL JOB WITH JOB ID AS PARAMETER");        
        try{
            if(!isset($jobId) || !isset($appId)){
                $this->logger->info('Job Id or App Id not specified');
                throw new ServiceException("Job Id / App Id not specified", 'jobid.or.appid.not.specified');
            }
            $appNewId = $this->getIdFromUuid('ox_app', $appId);
            if($appNewId != 0){
                $appId = $appNewId;
            }
            $this->logger->info("appId is : ");
            $this->logger->info($appId);
            if(isset($jobId)){
                $query = 'select * from ox_job where job_id = :jobId and app_id = :appId';
                $params = array('jobId' => $jobId, 'appId' => $appId);
                $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
                if(empty($result)){
                    throw new ServiceException("Job Not found", 'job.not.found');                    
                }
            }
            if(!isset($groupName)){
                $query = 'SELECT group_name from ox_job where job_id = :jobId and app_id = :appId';
                $params = array('jobId' => $jobId, 'appId' => $appId);
                $result = $this->executeQuerywithBindParameters($query, $params)->toArray();
                $this->logger->info("the response from job table is : ".print_r($result, true));
            }
            $url = 'canceljob';            
            $jobPayload = array('jobid' => $jobId, 'jobgroup' => $groupName);
            $response = $this->restClient->postWithHeader($url, $jobPayload);
            $this->logger->info("Response - " . print_r($response, true));
            if (!isset($response) && !isset($response['body'])) {
                throw new ServiceException("Schedule Job Error", 'schedule.job.exception');            
            }
        }
        catch (Exception $e){
            $this->logger->info("cancel job failed".$e->getMessage());
            throw $e;
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
        try{
            $appId = $this->getIdFromUuid('ox_app', $appId);
            $this->logger->info("EXECUTING GET JOB ID DETAILS ");
            $query = "SELECT * from ox_job where job_id = :jobId and app_id = :appId";
            $params = array('jobId' => $jobId, 'appId' => $appId);
            $this->logger->info("Job Service - GetJobDetails query - $query");
            $result = $this->executeQuerywithBindParameters($query, $params)->toArray();  
            if(empty($result)){
                throw new ServiceException("No records found", "no.records.found");
            }
        }
        catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
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
            if(!empty($result)){
                return $result;
            }
            else{
                throw new ServiceException("No records found", "no.records.found");            
            }
        }
        catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }
}