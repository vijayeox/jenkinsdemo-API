  package com.oxzion.camunda

  import org.camunda.bpm.engine.delegate.JavaDelegate
  import org.camunda.bpm.engine.delegate.DelegateExecution
  import org.camunda.bpm.engine.delegate.DelegateTask
  import org.slf4j.Logger
  import org.slf4j.LoggerFactory
  import groovy.util.ConfigSlurper
  import java.text.SimpleDateFormat 
  import groovy.json.*

  public class HttpDelegate implements JavaDelegate {

    private static final Logger logger = LoggerFactory.getLogger(HttpDelegate.class);

    public def getConnection(String url){
        def config = getConfig()
        String configUrl = config.callback.Url
        def baseUrl = new URL("${configUrl}/${url}")
        println baseUrl
        return baseUrl.openConnection()
    }

    public void execute(DelegateExecution execution) throws Exception {
      String data = execution.getVariables();
      String url = data['url'];
      remove(data['url']);
      String json = new JsonBuilder(data ).toPrettyString()
      postData(url,json)
    }

    private def getConfig(){
      String url = HttpDelegate.class.classLoader.getResource("config.groovy")
      println url
      return new ConfigSlurper().parse(new URL(url))
    }

    protected def postData(String url,String json){
        def connection = getConnection(url)
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
