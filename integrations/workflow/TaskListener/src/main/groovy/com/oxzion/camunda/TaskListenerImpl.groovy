  package com.oxzion.camunda

  import org.camunda.bpm.engine.delegate.TaskListener
  import org.camunda.bpm.engine.delegate.DelegateTask
  import groovy.util.ConfigSlurper
  import java.text.SimpleDateFormat 
  import groovy.json.*

  public class TaskListenerImpl implements TaskListener {

    public def getConnection(){
        def config = getConfig()
        String url = config.callback.Url
        def baseUrl = new URL("${url}/activityInstance")
        println baseUrl
        return baseUrl.openConnection()
    }
    
    @Override
    public void notify(DelegateTask task) {
      Map taskDetails = [:]
      taskDetails.name = task.name
      taskDetails.assignee = task.assignee
      String pattern = "dd-MM-yyyy";
      SimpleDateFormat simpleCreateDateFormat = new SimpleDateFormat(pattern)
      taskDetails.createTime = simpleCreateDateFormat.format(task.createTime)
      

      taskDetails.dueDate = task.dueDate ? simpleCreateDateFormat.format(task.dueDate) : task.dueDate
      
      def execution = task.execution
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
      String url = TaskListenerImpl.class.classLoader.getResource("config.groovy")
      println url
      return new ConfigSlurper().parse(new URL(url))
    }
    

  }
