package org.oxzion.processengine

import groovy.json.JsonBuilder
import org.camunda.bpm.engine.delegate.DelegateExecution
import org.camunda.bpm.engine.delegate.ExecutionListener
import org.slf4j.Logger
import org.slf4j.LoggerFactory
  
class EventListener  implements ExecutionListener, Serializable {
    static final long serialVersionUID = -687671492884005053L
    private static Map instances = [:]
    private static final Logger logger = LoggerFactory.getLogger(EventListener.class);

    private String event

    protected EventListener(String event) { 
        this.event = event
    }

    static EventListener getInstance(String event) {
        def instance = instances[event]
        if(instance == null) {
            instance = new EventListener(event)
            instances[event] = instance
        }
        return instance
    }
    def getConnection(){
        String url = getConfig()
        logger.info("Event ${this.event}")
        def baseUrl = new URL("${url}/callback/workflowinstance/start")
        if(this.event == "end"){
            baseUrl = new URL("${url}/callback/workflowinstance/complete")
        }
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
    void notify(DelegateExecution execution) {
        Map taskDetails = [:]
        taskDetails.processInstanceId = execution.processInstanceId
        taskDetails.variables = execution.getVariables()
        taskDetails.activityInstanceId = execution.getActivityInstanceId()
        taskDetails.parentInstanceId = execution.getParentActivityInstanceId()
        taskDetails.parentActivity = execution.getParentId()
        String json = new JsonBuilder(taskDetails).toPrettyString()
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
            logger.info("Response got - ${response}")
        }
    }
}
