package org.oxzion.processengine

import groovy.json.JsonBuilder
import groovy.json.JsonOutput
import groovy.sql.Sql
import org.camunda.bpm.engine.delegate.BpmnError
import org.camunda.bpm.engine.delegate.DelegateExecution
import org.camunda.bpm.engine.runtime.Incident
import org.oxzion.messaging.Publisher
import org.slf4j.Logger
import org.slf4j.LoggerFactory

import java.text.SimpleDateFormat

class ErrorHandler {

    private static final Logger logger = LoggerFactory.getLogger(ErrorHandler.class)

    private static def getConfig(){
        def properties = new Properties()
        this.getClass().getResource( '/application.properties' ).withInputStream {
            properties.load(it)
        }
        return properties
    }

    static def properties = getConfig()

    static Publisher publisher = Publisher.getPublisher()

    static handleError(DelegateExecution execution, Exception e) throws Exception{
        logger.error("Custom Service Task Listener Exception-- Message : ${e.getMessage()},   Trace : ${e.getStackTrace()}")
        try{        
            Map message = [:]
            Map data = execution.getVariables()
            if(data .containsKey("command")){
                message.command = data.command
            } else if(data.containsKey("commands")){
                message.commands = data.commands
            }
            if(data.containsKey("app_id")){
                message.app_id = data.app_id
            }
            message.error = e.getMessage()
            String msg = new JsonBuilder(message).toPrettyString()
            String stacktrace = new JsonBuilder(e.getStackTrace()).toPrettyString()
            logger.info("Incident Message : ${msg}")
            Incident incident = execution.createIncident("failedJob",execution.getCurrentActivityId(),msg)
            data.incidentId = incident.getId()
            String content = new JsonBuilder(data).toPrettyString()
            logger.info("Incident Id : ${incident.getId()}")
            message.incidentId = incident.getId()
            def id = log('failedJob',stacktrace,content,new JsonBuilder(message).toPrettyString(),message.app_id.toString())
        }catch(Exception ex){
            logger.error("Error while processing exception", ex)
        }
        finally{
            throw new BpmnError("400","Service Task Failure")
        }
    }
    static handleError(DelegateExecution execution) throws Exception{
        try{
            Map config = [:]
            config.activityId = execution.getCurrentActivityId()
            config.processDefinitionId = execution.getProcessDefinitionId()
            String configuration = new JsonBuilder(config).toPrettyString()
            logger.info("Incident Configuration : ${configuration}")
            Map message = [:]
            Map data = execution.getVariables()
            if(data .containsKey("command")){
                message.command = data.command
            } else if(data.containsKey("commands")){
                message.commands = data.commands
            }
            if(data.containsKey("app_id")){
                message.app_id = data.app_id
            }
            message.error = "failed to execute "
            String msg = new JsonBuilder(message).toPrettyString()
            logger.info("Incident Message : ${msg}")
            Incident incident = execution.createIncident("failedJob",execution.getCurrentActivityId(),msg)
            data.incidentId = incident.getId()
            String content = new JsonBuilder(data).toPrettyString()
            logger.info("Incident Id : ${incident.getId()}")
            message.incidentId = incident.getId()
            def id = log('failedJob',msg,content,new JsonBuilder(message).toPrettyString(),message.app_id.toString())
        }catch(Exception excep){
            logger.error("Error while processing exception", excep)
        }
        finally{
            throw new BpmnError("400","Service Task Failure")
        }
    }
    static int log(String error_type, String  error_trace, String payload, String params,String app_id){
        def prop = properties
        def API_DB_URL =System.getenv('API_DB_URL')?System.getenv('API_DB_URL'):prop."api_db_url"
        def DB_DRIVER = System.getenv('DB_DRIVER')?System.getenv('DB_DRIVER'):prop."db_driver"
        def DB_USERNAME = System.getenv('DB_USERNAME')?System.getenv('DB_USERNAME'):prop."db_username"
        def DB_PASSWORD = System.getenv('DB_PASSWORD')?System.getenv('DB_PASSWORD'):prop."db_password"
        def sql = Sql.newInstance(API_DB_URL, DB_USERNAME, DB_PASSWORD, DB_DRIVER)
        def ids
        int id
        try {
            Map<String,Object> error = new HashMap<>()
            error.put("error_type",error_type)
            error.put("error_trace",error_trace)
            error.put("payload",payload)
            error.put("params",params)
            String error_json = JsonOutput.toJson(error)
            Date now = new Date()
            String pattern = "yyyy-MM-dd HH:mm:ss"
            SimpleDateFormat formatter = new SimpleDateFormat(pattern)
            String mysqlDateString = formatter.format(now)
            def appId = sql.firstRow("SELECT id FROM ox_app where uuid=?",[app_id])
            print(appId?.getProperty("id"))
            ids = sql.executeInsert("INSERT INTO ox_error_log (error_type, error_trace,payload,date_created,params,app_id) values (${error_type}, ${error_trace},${payload},${mysqlDateString}, ${params}, ${appId.getProperty("id")})")
            id = ids.get(0).get(0)
            Map<String,String> email = new HashMap<>()
            email.put("to",prop."to_address")
            email.put("body",error_json)
            email.put("subject","Error occurrence")
            String email_json = JsonOutput.toJson(email)
            publisher.sendQueue(email_json,'mail')
            System.out.println("handling ex")
        }  catch (IOException ex) {
            logger.error("Could not log the message", ex)
        } finally{
            sql.close()
            return id
        }
    }
}
