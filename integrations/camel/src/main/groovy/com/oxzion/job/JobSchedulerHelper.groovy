package com.oxzion.job

import groovy.json.JsonOutput
import org.quartz.*
import java.util.Date
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.stereotype.Component
import org.quartz.impl.matchers.GroupMatcher

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

    def listJob() {
        try{
            List<String> list = new ArrayList<>()
            for (String groupName : scheduler.getJobGroupNames()) {

                for (JobKey jobKey : scheduler.getJobKeys(GroupMatcher.jobGroupEquals(groupName))) {

                    String jobName = jobKey.getName();
                    String jobGroup = jobKey.getGroup();

                    //get job's trigger
                    List<Trigger> triggers = (List<Trigger>) scheduler.getTriggersOfJob(jobKey);
                    Date nextFireTime = triggers.get(0).getNextFireTime();
                    list.add("[jobName] : " + jobName + ", [groupName] : "
                            + jobGroup + ",  next fire time -" + nextFireTime)
                }

            }
            return list
        } catch(Exception ex){
            logger.error("JOB HANDLER LIST (EXCEPTION) --- ${ex}")
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
            if(jobDataObj.job.containsKey('group') && !jobDataObj.job.containsKey('name')) {
                String _group = jobDataObj.job.group.toString()
                return JobBuilder.newJob(JobHandler.class)
                        .withIdentity(UUID.randomUUID().toString(), _group)
                        .withDescription("Job")
                        .usingJobData(jobDataMap)
                        .storeDurably()
                        .build()
            }
            else if(!jobDataObj.job.containsKey('group') && jobDataObj.job.containsKey('name')){
                String _name = jobDataObj.job.name.toString()
                return JobBuilder.newJob(JobHandler.class)
                        .withIdentity(_name, "Job")
                        .withDescription("Job")
                        .usingJobData(jobDataMap)
                        .storeDurably()
                        .build()
            }
            else if(jobDataObj.job.containsKey('group') && jobDataObj.job.containsKey('name')){
                String _name = jobDataObj.job.name.toString()
                String _group = jobDataObj.job.group.toString()
                return JobBuilder.newJob(JobHandler.class)
                        .withIdentity(_name, _group)
                        .withDescription("Job")
                        .usingJobData(jobDataMap)
                        .storeDurably()
                        .build()
            }
            else {
                return JobBuilder.newJob(JobHandler.class)
                        .withIdentity(UUID.randomUUID().toString(), "Job")
                        .withDescription("Job")
                        .usingJobData(jobDataMap)
                        .storeDurably()
                        .build()
            }
        }
        else
            return null
    }

    private Trigger buildJobTrigger(JobDetail jobDetail, Map payload) {
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-mm-dd'T'hh:mm:ss")
        formatter.setTimeZone(TimeZone.getTimeZone("UTC"))
        if(payload.schedule.cron) {
            CronExpression cronExpression = new CronExpression(payload.schedule.cron)
            if(payload.job.containsKey('group') && !payload.job.containsKey('name')) {
                String _group = payload.job.group.toString()
                return TriggerBuilder.newTrigger()
                        .forJob(jobDetail)
                        .withIdentity(jobDetail.getKey().getName(), _group)
                        .withDescription("Job")
                        .withSchedule(CronScheduleBuilder.cronSchedule(cronExpression).withMisfireHandlingInstructionFireAndProceed())
                        .build()
            }
            else if(payload.job.containsKey('group') && !payload.job.containsKey('name')){
                String _name = payload.job.name.toString()
                return TriggerBuilder.newTrigger()
                        .forJob(jobDetail)
                        .withIdentity(_name, "Job")
                        .withDescription("Job")
                        .withSchedule(CronScheduleBuilder.cronSchedule(cronExpression).withMisfireHandlingInstructionFireAndProceed())
                        .build()
            }
            else if(payload.job.containsKey('group') && payload.job.containsKey('name')){
                String _name = payload.job.name.toString()
                String _group = payload.job.group.toString()
                return TriggerBuilder.newTrigger()
                        .forJob(jobDetail)
                        .withIdentity(_name, _group)
                        .withDescription("Job")
                        .withSchedule(CronScheduleBuilder.cronSchedule(cronExpression).withMisfireHandlingInstructionFireAndProceed())
                        .build()
            }
            else{
                return TriggerBuilder.newTrigger()
                    .forJob(jobDetail)
                    .withIdentity(jobDetail.getKey().getName(), "Job")
                    .withDescription("Job")
                    .withSchedule(CronScheduleBuilder.cronSchedule(cronExpression).withMisfireHandlingInstructionFireAndProceed())
                    .build()
            }
        }
    }

}
