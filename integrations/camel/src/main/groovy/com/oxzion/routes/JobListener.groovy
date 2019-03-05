package com.oxzion.routes

import com.oxzion.job.JobSchedulerHelper
import com.oxzion.job.JobValidation
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.RequestBody
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController
import org.springframework.web.bind.annotation.RequestMapping

@RestController
class JobListener {

    @Autowired
    JobValidation validation

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
    @RequestMapping(value ="/setupJob",method = RequestMethod.POST, consumes = "application/json")
    String setupJob(@RequestBody  Map<String, Object> payload) {
        //TODO validation
        Set keys = payload.keySet()
        println("All keys are: " + keys)
        Map<String,Object> Validations = new HashMap<>()
        Validations.put(1,"Job not defined")
        Validations.put(2,"Scheduler not defined")
            if(payload.containsKey('Job')){
                jobHelper.schedule(payload)
            } else {
               return "{ \"Message\" :"+"\""+Validations.get(1)+"\" }"
            }
        }
    }
