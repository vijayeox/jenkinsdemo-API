package org.oxzion.processengine

import groovy.json.JsonBuilder
import groovy.json.JsonSlurper
import org.camunda.bpm.engine.delegate.DelegateExecution
import org.camunda.bpm.engine.delegate.ExecutionListener
import org.slf4j.Logger
import org.slf4j.LoggerFactory

class CustomServiceTaskListener implements ExecutionListener {
  private static final Logger logger = LoggerFactory.getLogger(CustomServiceTaskListener.class);

  private static CustomServiceTaskListener instance = null

  protected CustomServiceTaskListener() { }

  def jsonSlurper = new JsonSlurper()

  static CustomServiceTaskListener getInstance() {
    if(instance == null) {
      instance = new CustomServiceTaskListener()
    }
    return instance
  }
  def getConnection(){
    String url = getConfig()
    def baseUrl = new URL("${url}/callback/workflow/servicetask")
    logger.info("Opening connection to ${baseUrl}")
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
    try{
      logger.info("Custom Service Task Listener -- ${taskDetails.variables.command}")
      def connection = getConnection()
      logger.info("Posting data - ${json}")
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
        def responseValue = jsonSlurper.parseText(response)
        if(responseValue.status == "success"){
            if(taskDetails.variables.return == "true"){
              def responseData = responseValue.data
              responseData = responseData.putAll(responseData)
              execution.setVariables(responseData)
              logger.info("Response received - ${execution.getVariables()}")
            }
        }else{
           //TODO ERROR HANDLER
           logger.error("ERROR");
        }
      }
      }catch(Exception e){
          logger.error("Custom Service Task Listener Exception-- Message : ${e.getMessage()},   Trace : ${e.getStackTrace()}")
      } 
  }
}
