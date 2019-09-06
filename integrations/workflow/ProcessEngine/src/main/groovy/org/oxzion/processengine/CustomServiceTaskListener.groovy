package org.oxzion.processengine

import groovy.json.JsonBuilder
import org.camunda.bpm.engine.delegate.DelegateExecution
import org.camunda.bpm.engine.delegate.ExecutionListener

class CustomServiceTaskListener implements ExecutionListener {

  private static CustomServiceTaskListener instance = null

  protected CustomServiceTaskListener() { }

  static CustomServiceTaskListener getInstance() {
    if(instance == null) {
      instance = new CustomServiceTaskListener()
    }
    return instance
  }
  def getConnection(){
    String url = getConfig()
    def baseUrl = new URL("${url}/callback/workflow/servicetask")
    println baseUrl
    return baseUrl.openConnection()
  }

  private def getConfig(){
    def properties = new Properties()
    this.getClass().getResource( '/application.properties' ).withInputStream {
      properties.load(it)
    }
    return properties."applicationurl"
  }

  @Override
  void notify(DelegateExecution execution) throws Exception {
    Map taskDetails = [:]
    taskDetails.activityInstanceId = execution.activityInstanceId
    taskDetails.processInstanceId = execution.processInstanceId
    taskDetails.variables = execution.getVariables()
    taskDetails.activityInstanceId = execution.getActivityInstanceId()
    taskDetails.parentInstanceId = execution.getParentActivityInstanceId()
    taskDetails.parentActivity = execution.getParentId()
    String json = new JsonBuilder(taskDetails ).toPrettyString()
    println json
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
}
