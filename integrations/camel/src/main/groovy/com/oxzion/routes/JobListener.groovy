package com.oxzion.routes

import com.oxzion.job.JobSchedulerHelper
import com.oxzion.job.JobSchedulerResponse
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.RequestBody
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController
import org.springframework.web.bind.annotation.RequestMapping
import org.slf4j.Logger
import org.slf4j.LoggerFactory

@RestController
class JobListener {
    private static final Logger logger = LoggerFactory.getLogger(JobListener.class);
    @Autowired
    JobSchedulerHelper jobHelper
    
    @RequestMapping(value ="/",method = RequestMethod.GET)
    String index() {
        return "Success!!"
    }
    
    /**
     *
     *
     * @param payload
     *          {
     *              job : {
     *                      url : string,
     *                      group : string,
     *                      topic : string,
     *                      data : {json}
     *              }
     *              schedule : {
     *                  cron : string cron expression
     *              }
     *          }
     * @return
     */

    @RequestMapping(value ="/setupjob",method = RequestMethod.POST, consumes = "application/json")
    Map setupJob(@RequestBody  Map<String, Object> payload/*,@RequestHeader("Authorization") Object auth*/ ) {
        /*payload.put("Authorization",auth)*/

        logger.info("RequestMapping - $payload")
        JobSchedulerResponse jobSchedulerResponse = new JobSchedulerResponse()

        if (payload.containsKey('job')) {
            if (!(payload.job.containsKey('url') || payload.job.containsKey('topic')))
                jobSchedulerResponse.setParams(false, "url or topic isn't defined")
        }
        else
            jobSchedulerResponse.setParams(false, "job isn't defined")

        if (payload.schedule) {
            if (!(payload.schedule?.cron))
                jobSchedulerResponse.setParams(false, "schedule inputs are incorrect")
        }
        else
            jobSchedulerResponse.setParams(false, "schedule isn't defined")

        if(jobSchedulerResponse.getAll())
            return jobSchedulerResponse.getAll()

        def jobDetail = jobHelper.schedule(payload)
        jobSchedulerResponse.setParams(true, "Job Scheduled Successfully!", jobDetail.getKey().getName(), jobDetail.getKey().getGroup())
        return jobSchedulerResponse.getAll()
    }
    /**
     *
     * @param payload
     * {
     *     jobId : string
     *     jobGroup : string
     * }
     * @return
     */
    @RequestMapping(value ="/canceljob",method = RequestMethod.POST, consumes = "application/json")
        Map cancelJob(@RequestBody  Map<String, Object> payload ) {
        logger.info("RequestMapping - $payload")
        JobSchedulerResponse jobSchedulerResponse = new JobSchedulerResponse()
        if (payload.containsKey('jobid')) {
            if(payload.containsKey('jobgroup')) {
                def check = jobHelper.cancelJob(payload.jobid, payload.jobgroup)
                if (check)
                    jobSchedulerResponse.setParams(true, "Job Canceled Successfully!", payload.jobid, payload.jobgroup)
                else
                    throw new NotFoundException()
            }
        }
        else
            jobSchedulerResponse.setParams(false,"Data incorrectly specified")

        return jobSchedulerResponse.getAll()
    }

    @RequestMapping(value ="/listjob",method = RequestMethod.GET)
    def ListJob() {
        logger.info("List jobs - $payload")
        def check = jobHelper.listJob()
        return check
    }

}

