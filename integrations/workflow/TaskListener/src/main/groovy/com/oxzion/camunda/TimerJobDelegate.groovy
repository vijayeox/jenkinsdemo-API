  package com.oxzion.camunda

  import org.camunda.bpm.engine.delegate.JavaDelegate
  import org.camunda.bpm.engine.delegate.DelegateExecution
  import org.camunda.bpm.engine.delegate.DelegateTask
  import groovy.util.ConfigSlurper
  import org.slf4j.Logger
  import org.slf4j.LoggerFactory
  import java.text.SimpleDateFormat 
  import groovy.json.*

  public class TimerJobDelegate extends HttpDelegate {

    private static final Logger logger = LoggerFactory.getLogger(TimerJobDelegate.class);

    public void execute(DelegateExecution execution) throws Exception {
      String data = execution.getVariables()
      String url = data['url']
      String jobUrl = data['jobUrl']
      String cron = data['cron']
      remove(data['url'])
      remove(data['cron'])
      
      def setUpJob = ["job": ["url" : jobUrl,"data" : data ],"schedule" : ["cron" : cron]]
      String json = new JsonBuilder(setUpJob ).toPrettyString()
      this.postData(url, json)
    }

  }
