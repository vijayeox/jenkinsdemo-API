package org.oxzion.processengine

import groovy.json.JsonBuilder
import org.camunda.bpm.engine.delegate.DelegateTask
import org.camunda.bpm.engine.delegate.TaskListener
import org.camunda.bpm.engine.task.IdentityLink

import java.text.SimpleDateFormat
import java.util.logging.Logger


class CustomTaskCompleteListener implements TaskListener, Serializable {

    static final long serialVersionUID = -787991492884005035L

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
        Map taskDetails = [:]
        taskDetails.name = delegateTask.name
        def candidatesArray = []
        def i=0
        for (IdentityLink item : delegateTask.getCandidates()){
          Map candidateList = [:]
          candidateList.groupid = item.getGroupId()
          candidateList.type = item.getType()
          candidateList.userid = item.getUserId()
          candidatesArray[i] = candidateList
          i++
      }
      taskDetails.candidates = candidatesArray
      taskDetails.owner = delegateTask.getOwner()
      taskDetails.assignee = delegateTask.getAssignee()
      taskDetails.taskId = delegateTask.getTaskDefinitionKey()
      String pattern = "dd-MM-yyyy"
      SimpleDateFormat simpleCreateDateFormat = new SimpleDateFormat(pattern)
      taskDetails.createTime = simpleCreateDateFormat.format(delegateTask.createTime)
      taskDetails.dueDate = delegateTask.dueDate ? simpleCreateDateFormat.format(delegateTask.dueDate) : delegateTask.dueDate
      taskDetails.executionId = delegateTask.getExecutionId()
      def execution = delegateTask.execution
      def processInstance = execution.getProcessInstance()
      taskDetails.processVariables = processInstance.getVariables()
      taskDetails.activityInstanceId = delegateTask.getId()
      taskDetails.executionActivityinstanceId = execution.activityInstanceId
      taskDetails.processInstanceId = execution.processInstanceId
      taskDetails.variables = execution.getVariables()
      taskDetails.parentActivity = execution.getParentActivityInstanceId()
      taskDetails.currentActivity = execution.getCurrentActivityId()
      taskDetails.parent = execution.getParentId()
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
