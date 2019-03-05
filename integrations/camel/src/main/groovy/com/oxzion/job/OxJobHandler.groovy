package com.oxzion.job


import groovy.json.JsonBuilder
import groovy.json.JsonSlurper
import org.quartz.Job
import org.quartz.JobExecutionContext
import org.quartz.JobExecutionException

public class OxJobHandler implements Job{

   // private static Logger _log = LoggerFactory.getLogger(OxJobHandler.class);

    public OxJobHandler() {

	}

    public void execute(JobExecutionContext context)
            throws JobExecutionException
    {
        //SAMPLE JSON OBJECT
        def jsonBuilder = new JsonBuilder()
        def sampleString = jsonBuilder {
            url  'http://localhost:8080/project'
            data '{"username":"bharatg","password":"password" }'
        }

        //PARSE SAMPLE JSON OBJECT. REPLACE JSONBUILDER LATER
        String jsonString = jsonBuilder.toString()
        def slurper = new JsonSlurper()
        def result = slurper.parseText(jsonString)
        String dataStringTrim = slurper.parseText(result.data).toString().replaceAll(",","&").replaceAll(" ","")
        String dataString = dataStringTrim.substring(1, dataStringTrim.length()-1)
        println(dataString)
/*
        def baseUrl = new URL('http://localhost:8080/auth')
        def connection = baseUrl.openConnection()
        connection.with {
            doOutput = true
            setRequestProperty('Content-Type','application/json')
            requestMethod = 'POST'
            outputStream.withWriter { writer ->
                writer << result.data
            }
            println content.text
        }*/


        //AUTHORIZATION API CALL TO GENERATE JWT TOKEN
        def postAuth = new URL("http://localhost:8080/auth").openConnection()
        def message = 'username=bharatg&password=password'
        postAuth.setRequestMethod("POST")
        postAuth.setDoOutput(true)
        postAuth.getOutputStream().write(message.getBytes())
        def postAuthRC = postAuth.getResponseCode()
        println("The Response of authorization api call is =" + postAuthRC);
        if(postAuthRC.equals(200)) {
            String auth_success_json =postAuth.getInputStream().getText()
            def jwt = slurper.parseText(auth_success_json)
            String jwtToken = "Bearer "+jwt.data.jwt

        //HANDLE IF URL IS PRESENT
        if(result.url) {
            String userSpecifiedUrl = result.url
            if (result.data) {

            }
            else{
                    def get = new URL(userSpecifiedUrl).openConnection();
                    get.setRequestProperty("Authorization", jwtToken)
                    def getRC = get.getResponseCode();
                    println("The Response of GET api call is " + getRC);
                    if(getRC.equals(200)) {
                        println(get.getInputStream().getText());
                    }
                }
            }
        }
        else {
            println("Url Does not exist")
        }
        println(result)
        println "in execute"
    }

}
