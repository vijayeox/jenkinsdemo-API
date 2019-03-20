package com.oxzion.routes

import com.oxzion.job.JobSchedulerHelper
import com.oxzion.job.JobSchedulerResponse
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.RequestBody
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController
import org.springframework.web.bind.annotation.RequestMapping

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
     *                  cron : string cron expression
     *              }
     *          }
     * @return
     */

    @RequestMapping(value ="/setupjob",method = RequestMethod.POST, consumes = "application/json")
    Map setupJob(@RequestBody  Map<String, Object> payload/*,@RequestHeader("Authorization") Object auth*/ ) {
        /*payload.put("Authorization",auth)*/

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

}
