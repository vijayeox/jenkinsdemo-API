package org.oxzion.processengine

import groovy.json.JsonBuilder
import org.camunda.bpm.engine.delegate.DelegateTask
import org.camunda.bpm.engine.delegate.TaskListener

import java.text.SimpleDateFormat
import java.util.logging.Logger


class CustomTaskCompleteListener implements TaskListener {
    private final Logger LOGGER = Logger.getLogger(this.getClass().getName());

    private static CustomTaskCompleteListener instance = null

    protected CustomTaskCompleteListener() { }

    static CustomTaskCompleteListener getInstance() {
        if(instance == null) {
            instance = new CustomTaskCompleteListener()
        }
        return instance
    }
    def getConnection(){
        String url = getConfig()
        def baseUrl = new URL("${url}/callback/workflow/activitycomplete")
        println baseUrl
        return baseUrl.openConnection()
    }

    void notify(DelegateTask delegateTask) {
        String assignee = delegateTask.getAssignee()
        Map taskDetails = [:]
        taskDetails.name = delegateTask.name
        taskDetails.candidates = delegateTask.getCandidates()
        taskDetails.owner = delegateTask.getOwner()
        taskDetails.assignee = delegateTask.assignee
        taskDetails.taskId = delegateTask.getTaskDefinitionKey()
        String pattern = "dd-MM-yyyy"
        SimpleDateFormat simpleCreateDateFormat = new SimpleDateFormat(pattern)
        taskDetails.createTime = simpleCreateDateFormat.format(delegateTask.createTime)
        taskDetails.dueDate = delegateTask.dueDate ? simpleCreateDateFormat.format(delegateTask.dueDate) : delegateTask.dueDate
        def execution = delegateTask.execution
        def processInstance = execution.getProcessInstance()
        taskDetails.processVariables = processInstance.getVariables()
        taskDetails.activityInstanceId = execution.activityInstanceId
        taskDetails.processInstanceId = execution.processInstanceId
        taskDetails.variables = execution.getVariables()
        String json = new JsonBuilder(taskDetails).toPrettyString()
        println json
        //TODO http callback using the base url above
        def connection = getConnection()
        String response
        connection.with {
            doOutput = true
            requestMethod = 'POST'
            outputStream.withWriter { writer ->
                writer << json
            }
            response = inputStream.withReader{ reader ->
                reader.text
            }
            println response
        }
    }
    private def getConfig(){
        def properties = new Properties()
        this.getClass().getResource( '/application.properties' ).withInputStream {
            properties.load(it)
        }
        return properties."applicationurl"
    }
}
