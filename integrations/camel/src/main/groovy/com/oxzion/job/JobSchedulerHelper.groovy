package com.oxzion.job

import groovy.json.JsonOutput
import org.quartz.*
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.stereotype.Component

import java.text.SimpleDateFormat

@Component
class JobSchedulerHelper {

    private static final Logger logger = LoggerFactory.getLogger(JobSchedulerHelper.class);

    @Autowired
    private Scheduler scheduler

    def schedule(Map payload) {
        try {
            JobDetail jobDetail = buildJobDetail(payload)
            Trigger trigger = buildJobTrigger(jobDetail, payload)
            scheduler.scheduleJob(jobDetail, trigger)
            return jobDetail
        }
        catch (SchedulerException se) {
            se.printStackTrace()
        }
    }

    def cancelJob(String jobId, String jobGroup) {
            try {
                JobKey jobKey = JobKey.jobKey(jobId, jobGroup)
                def check = scheduler.deleteJob(jobKey)
                return check
            } catch(Exception ex){
                logger.error("JOB HANDLER CANCEL (EXCEPTION) --- ${ex}")
            }
    }

    private JobDetail buildJobDetail(Map jobDataObj) {
        if(!jobDataObj.isEmpty()) {
            JobDataMap jobDataMap = new JobDataMap()
            def jobDataJson = JsonOutput.toJson(jobDataObj)
            jobDataMap.put("JobData",jobDataJson)
            return JobBuilder.newJob(JobHandler.class)
                    .withIdentity(UUID.randomUUID().toString(), "Job")
                    .withDescription("Job")
                    .usingJobData(jobDataMap)
                    .storeDurably()
                    .build()
        }
        else
            return null
    }

    private Trigger buildJobTrigger(JobDetail jobDetail, Map payload) {
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-mm-dd'T'hh:mm:ss")
        formatter.setTimeZone(TimeZone.getTimeZone("UTC"))
        if(payload.schedule.cron) {
            CronExpression cronExpression = new CronExpression(payload.schedule.cron)
            return TriggerBuilder.newTrigger()
                    .forJob(jobDetail)
                    .withIdentity(jobDetail.getKey().getName(), "Job")
                    .withDescription("Job")
                    .withSchedule(CronScheduleBuilder.cronSchedule(cronExpression).withMisfireHandlingInstructionFireAndProceed())
                    .build()
        }
    }

}
