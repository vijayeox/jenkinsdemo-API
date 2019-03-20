package com.oxzion.job

import com.oxzion.activemq.Publisher
import com.oxzion.activemq.Sender
import org.quartz.JobDataMap
import org.quartz.JobExecutionContext
import org.quartz.JobExecutionException
import groovy.json.JsonGenerator
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.context.annotation.PropertySource
import org.springframework.core.env.Environment
import org.springframework.scheduling.quartz.QuartzJobBean
import groovy.json.JsonSlurper
import groovy.json.JsonOutput


@PropertySource("classpath:oxzion.properties")
class JobHandler extends QuartzJobBean {

    private static Publisher publisher

    @Autowired
    private Sender sender

    @Autowired
    private Environment env
    private static final Logger logger = LoggerFactory.getLogger(JobHandler.class)

    @Override
    protected void executeInternal(JobExecutionContext jobExecutionContext) throws JobExecutionException {
        logger.info("Executing Job with key {}", jobExecutionContext.getJobDetail().getKey())
        JobDataMap jobDataMap = jobExecutionContext.getMergedJobDataMap()
        String urlResponse
        def slurper = new JsonSlurper()
        if (jobDataMap.JobData.job.url) {
            String authresponse = postAuth('apikey='+env.getProperty("apikey"))
            def jwt = slurper.parseText(authresponse)
            if((jobDataMap.JobData.job.data).isEmpty())
                urlResponse = get(jobDataMap.JobData.job.url, "Bearer " + jwt.data.jwt)
            else{
                urlResponse = post(jobDataMap.JobData.job.url,"Bearer "+jwt.data.jwt,jobDataMap.JobData.job.data)
            }
            println(urlResponse)
        }
        if (jobDataMap.JobData.job.topic){
            def json = JsonOutput.toJson(jobDataMap.JobData.job.data)
            String data = json.toString()
           // sender.send(data,jobDataMap.JobData.job.topic)  -- use this for sending jms messages
            publisher = new Publisher();
            publisher.create("publisher",jobDataMap.JobData.job.topic)
            publisher.sendData(data)
            publisher.closeConnection()
        }
    }

    public String postAuth(String data)
    {
        def postAuth = new URL(env.getProperty("authorization-url")).openConnection()
        def message = data
        String auth_success_json
        postAuth.setRequestMethod("POST")
        postAuth.setDoOutput(true)
        postAuth.getOutputStream().write(message.getBytes())
        def postAuthRC = postAuth.getResponseCode()
        println("The Response of authorization api call is =" + postAuthRC);
        if (postAuthRC.equals(200)) {
            auth_success_json = postAuth.getInputStream().getText()
        }
        postAuth.disconnect()
        return  auth_success_json
    }

    public String get(String url,String jwt)
    {
        def get = new URL(url).openConnection()
        get.setRequestProperty("Authorization", jwt)
        def getRC = get.getResponseCode()
        println("The Response of GET api call is " + getRC)
        String response = get.getInputStream().getText()
        get.disconnect()
        if(getRC.equals(200)) {
           return response
        }
    }

    public String post(String url, String jwt,LinkedHashMap dataMap)
    {
        def jsonDefaultOutput = new JsonGenerator.Options().build()
        def jsonDefaultResult = jsonDefaultOutput.toJson(dataMap)
        def postAuth = new URL(url).openConnection()
        if(!url.contains('auth'))
            postAuth.setRequestProperty("Authorization",jwt)
        def message = jsonDefaultResult.toString()
        String auth_success_json
        postAuth.setRequestMethod("POST")
        postAuth.setDoOutput(true)
        postAuth.setRequestProperty("Content-Type", "application/json")
        postAuth.getOutputStream().write(message.getBytes("UTF-8"))
        def postAuthRC = postAuth.getResponseCode()
        println("The Response of authorization api call is =" + postAuthRC);
        if (postAuthRC.equals(200)) {
            auth_success_json = postAuth.getInputStream().getText()
        }
        postAuth.disconnect()
        return  auth_success_json
    }
}
