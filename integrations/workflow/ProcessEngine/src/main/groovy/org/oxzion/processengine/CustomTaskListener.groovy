package org.oxzion.processengine

import groovy.json.JsonBuilder
import org.camunda.bpm.engine.delegate.DelegateTask
import org.camunda.bpm.engine.delegate.TaskListener

import java.text.SimpleDateFormat
import java.util.logging.Logger


class CustomTaskListener implements TaskListener {
  private final Logger LOGGER = Logger.getLogger(this.getClass().getName());

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
    def baseUrl = new URL("${url}/activityInstance")
    println baseUrl
    return baseUrl.openConnection()
  }

  void notify(DelegateTask delegateTask) {
    String assignee = delegateTask.getAssignee()
    LOGGER.info("Hello " + assignee + "! Please start to work on your task " + delegateTask.getDescription())
    Map taskDetails = [:]
    taskDetails.name = delegateTask.name
    taskDetails.assignee = delegateTask.assignee
    String pattern = "dd-MM-yyyy"
    SimpleDateFormat simpleCreateDateFormat = new SimpleDateFormat(pattern)
    taskDetails.createTime = simpleCreateDateFormat.format(delegateTask.createTime)
    taskDetails.dueDate = delegateTask.dueDate ? simpleCreateDateFormat.format(delegateTask.dueDate) : delegateTask.dueDate
    def execution = delegateTask.execution
    taskDetails.activityInstanceId = execution.activityInstanceId
    taskDetails.processInstanceId = execution.processInstanceId
    String json = new JsonBuilder(taskDetails ).toPrettyString()
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
