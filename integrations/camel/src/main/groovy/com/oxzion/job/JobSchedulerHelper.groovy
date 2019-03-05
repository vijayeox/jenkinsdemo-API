package com.oxzion.job


import org.quartz.*
import org.quartz.impl.StdSchedulerFactory
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.stereotype.Component

import static org.quartz.TriggerBuilder.newTrigger


@Component
class JobSchedulerHelper {

    private static final Logger logger = LoggerFactory.getLogger(JobSchedulerHelper.class);

    @Autowired
    private Scheduler scheduler

    void  schedule(Map payload) {

        try {
//TODO use the schedule details passed in the

            JobDetail jobDetail = buildJobDetail(payload)
            /*Trigger trigger = buildJobTrigger(jobDetail, dateTime);*/
            if(!jobDetail) {
                Trigger trigger = newTrigger().forJob(jobDetail)
                        .withIdentity(jobDetail.getKey().getName(), "Job")
                        .withDescription("Job")
                        .startNow()
                        .build()
                scheduler.scheduleJob(jobDetail, trigger)
                //Shutdown hook?
            }
            // Grab the Scheduler instance from the Factory
            /*SchedulerFactory schedulerFactory = new StdSchedulerFactory()
            Scheduler scheduler = schedulerFactory.getScheduler()
            JobDetail job = JobBuilder.newJob(OxJobHandler.class).withIdentity("myJob", "group1").build()
            Trigger trigger = newTrigger().withIdentity("myTrigger", "group1").startNow().build()
            scheduler.scheduleJob(job, trigger)
            scheduler.start()
            Thread.sleep(90L * 1000L)
            scheduler.shutdown() */

        }
        catch (SchedulerException se) {
            se.printStackTrace()
        }

    }

    private JobDetail buildJobDetail(Map jobDataObj) {

        if(!jobDataObj.isEmpty()) {
            JobDataMap jobDataMap = new JobDataMap()
            jobDataMap.put("JobData",jobDataObj)
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

    /*private Trigger buildJobTrigger(JobDetail jobDetail, ZonedDateTime startAt) {
        return TriggerBuilder.newTrigger()
                .forJob(jobDetail)
                .withIdentity(jobDetail.getKey().getName(), "email-triggers")
                .withDescription("Send Email Trigger")
                .startAt(Date.from(startAt.toInstant()))
                .withSchedule(SimpleScheduleBuilder.simpleSchedule().withMisfireHandlingInstructionFireNow())
                .build();
    }*/
}
