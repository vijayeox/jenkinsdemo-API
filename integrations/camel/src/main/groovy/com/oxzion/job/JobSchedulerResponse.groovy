package com.oxzion.job

class JobSchedulerResponse {
    private boolean success
    private String jobId
    private String jobGroup
    private String message
    private def response = [:]

    public void setParams(boolean success, String message, String jobId = null, String jobGroup = null) {
        this.success = success
        this.message = message
        response.put('Success',success)
        response.put('Message',message)
        if(jobId){
            this.jobId = jobId
            response['JobId'] = jobId
        }
        if(jobGroup){
            this.jobGroup = jobGroup
            response['JobGroup'] = jobGroup
        }
    }

    public boolean isSuccess() {
        return success
    }

    public void setSuccess(boolean success) {
        this.success = success
        response.put('Success',this.success)
    }

    public String getJobId() {
        return jobId
    }

    public void setJobId(String jobId) {
        this.jobId = jobId
        response.put('JobId',this.jobId)
    }

    public String getJobGroup() {
        return jobGroup
    }

    public void setJobGroup(String jobGroup) {
        this.jobGroup = jobGroup
        response.put('JobGroup',this.jobGroup)
    }

    public String getMessage() {
        return message
    }

    public void setMessage(String message) {
        this.message = message
        response.put('Message',this.message)
    }

    def getAll() {
        return response
    }
}
