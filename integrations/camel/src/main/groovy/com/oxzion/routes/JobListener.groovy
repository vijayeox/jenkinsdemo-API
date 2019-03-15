package com.oxzion.routes

import com.oxzion.job.JobSchedulerHelper
import com.oxzion.job.JobSchedulerResponse
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.RequestBody
import org.springframework.web.bind.annotation.RequestHeader
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.http.HttpHeaders

@RestController
class JobListener {

    @Autowired
    JobSchedulerHelper jobHelper

    /**
     *
     *
     * @param payload
     *          {
     *              job : {
     *                      url : string,
     *                      topic : string,
     *                      data : {json}
     *              }
     *              schedule : {
     *                  start : {
     *                      datetime : datetime,
     *                      relative : {
     *                          interval : numeric,
     *                          units : string with any of these DAY | HOUR | MILLISECOND | MINUTE | MONTH | SECOND | WEEK | YEAR
     *                      }
     *                  },
     *                  interval : {
     *                      period : numeric
     *                      units : string with any of these DAY | HOUR | MILLISECOND | MINUTE | MONTH | SECOND | WEEK | YEAR
     *                  },
     *                  cron : string cron expression,
     *                  repeatCount : numeric -1 for ever, number of time the trigger will repeat - total number of
     *  firings will be this number + 1.
     *
     *              }
     *          }
     * @return
     */
    @RequestMapping(value ="/setupjob",method = RequestMethod.POST, consumes = "application/json")
    Map setupJob(@RequestBody  Map<String, Object> payload/*,@RequestHeader("Authorization") Object auth*/ ) {
        //TODO validation
        /*payload.put("Authorization",auth)*/
        JobSchedulerResponse jobSchedulerResponse = new JobSchedulerResponse()

        if(payload.containsKey('job')){
            if(!(payload.job.containsKey('url')||payload.job.containsKey('topic')))
                jobSchedulerResponse.setParams(false,"Url or Topic isn't defined")
        }
        else
            jobSchedulerResponse.setParams(false, "Job isn't defined")

        if(payload.containsKey('schedule')) {
            if (!(((payload.schedule.containsKey('start') || payload.schedule.containsKey('interval')) && payload.schedule.containsKey('repeatCount')) || (payload.schedule.containsKey('cron'))))
                jobSchedulerResponse.setParams(false, "Scheduler not provided with correct inputs")
        }
        else
            jobSchedulerResponse.setParams(false, "Scheduler isn't defined")

        if(jobSchedulerResponse.getAll())
            return jobSchedulerResponse.getAll()

        def jobDetail = jobHelper.schedule(payload)
        jobSchedulerResponse.setParams(true, "Job Scheduled Successfully!", jobDetail.getKey().getName(), jobDetail.getKey().getGroup())
        return jobSchedulerResponse.getAll()
    }

}
