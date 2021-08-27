package org.oxzion.processengine

import groovy.json.JsonBuilder
import org.camunda.bpm.engine.delegate.DelegateTask
import org.camunda.bpm.engine.delegate.TaskListener
import org.camunda.bpm.engine.task.IdentityLink

import java.text.SimpleDateFormat
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import java.util.regex.Matcher


class CustomTaskListener implements TaskListener, Serializable {
  static final long serialVersionUID = -687991492884005033L
  private static final Logger logger = LoggerFactory.getLogger(CustomTaskListener.class);

  private static CustomTaskListener instance = null

  protected CustomTaskListener() { }

  static CustomTaskListener getInstance() {
    if(instance == null) {
      instance = new CustomTaskListener()
    }
    return instance
  }
  def getConnection(){
    String url = getConfig()
    def baseUrl = new URL("${url}/callback/workflow/activityinstance")
    logger.info("Opening connection to ${baseUrl}")
    return baseUrl.openConnection()
  }

  void notify(DelegateTask delegateTask) {
    Map taskDetails = [:]
    taskDetails.name = delegateTask.name

    def execution = delegateTask.execution
    def candidatesArray = []
    def i=0
    def reg1 = /\{\{[A-Za-z0-9]*\}\}/
    def reg2 = /\{\{role:[A-Za-z]*\}\}/
    def reg3 = /\{\{group:[A-Za-z]*\}\}/
    def reg4 = /\{\{participant:[A-Za-z0-9_-]*\}\}/
    taskDetails.variables = execution.getVariables()
    for (IdentityLink item : delegateTask.getCandidates()){
      Map candidateList = [:]
      candidateList.type = item.getType()
      def userId = item.getUserId()
      def  typeArray = [] 
      if(userId ==~ reg1){
        def val = userId.substring(2, userId.length()-2)
        candidateList.userid = taskDetails.variables[val] ? taskDetails.variables[val]  : item.getUserId();
      }else{
        candidateList.userid = item.getUserId();
      }
      if(userId ==~ reg4){
        def val = userId.substring(2, userId.length()-2)
        typeArray = val.split(":")
        candidateList.participant = typeArray[1]
      }
      if(userId ==~ reg2){
        def val = userId.substring(2, userId.length()-2)
        typeArray = val.split(":")
        candidateList.roleid = typeArray[1]
      }else if(userId ==~ reg3){
        def val = userId.substring(2, userId.length()-2)
        typeArray = val.split(":")
        candidateList.groupid = typeArray[1]
      }else{
        candidateList.groupid = item.getGroupId()
      }
      candidatesArray[i] = candidateList
      i++
    }
    taskDetails.candidates = candidatesArray
    taskDetails.owner = delegateTask.getOwner()
    taskDetails.assignee = delegateTask.getAssignee()
    logger.info("Task Data")
   
    println "Task Assignee - ${taskDetails.assignee}"
    println "Task Assignee match - ${taskDetails.assignee ==~ reg1}"
    if(taskDetails.assignee ==~ reg1){
      logger.info("TaskAssigneeList")
      println "TaskAssigneeList"
      def val = taskDetails.assignee.substring(2, taskDetails.assignee.length()-2)
      taskDetails.assignee = taskDetails.variables[val] ? taskDetails.variables[val] : taskDetails.assignee
      logger.info("Task Assignee List End")
      println "Task Assignee List End"
    }
    logger.info("Task Assignee - ${taskDetails.assignee}")
    println "Task Assignee - ${taskDetails.assignee}"
    taskDetails.status = "in_progress"
    taskDetails.taskId = delegateTask.getTaskDefinitionKey()
    String pattern = "dd-MM-yyyy"
    SimpleDateFormat simpleCreateDateFormat = new SimpleDateFormat(pattern)
    taskDetails.createTime = simpleCreateDateFormat.format(delegateTask.createTime)
    taskDetails.dueDate = delegateTask.dueDate ? simpleCreateDateFormat.format(delegateTask.dueDate) : delegateTask.dueDate
    taskDetails.executionId = delegateTask.getExecutionId()
    def processInstance = execution.getProcessInstance()
    taskDetails.processVariables = processInstance.getVariables()
    taskDetails.activityInstanceId = delegateTask.getId()
    taskDetails.executionActivityinstanceId = execution.activityInstanceId
    taskDetails.processInstanceId = execution.processInstanceId
    taskDetails.parentActivity = execution.getParentActivityInstanceId()
    taskDetails.currentActivity = execution.getCurrentActivityId()
    taskDetails.parent = execution.getParentId()
    String json = new JsonBuilder(taskDetails ).toPrettyString()
    logger.info("Posting data - ${json}")
    println "Posting data - ${json}"
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
      logger.info("Response received - ${response}")
      println "Response received - ${response}"
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
