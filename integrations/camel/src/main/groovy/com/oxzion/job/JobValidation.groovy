package com.oxzion.job

import org.springframework.stereotype.Component

@Component
class JobValidation extends Exception {
    def myList = ["Job not defined","Scheduler not defined"]
    public String get(int id)
    {
        return myList[id]
    }
}
