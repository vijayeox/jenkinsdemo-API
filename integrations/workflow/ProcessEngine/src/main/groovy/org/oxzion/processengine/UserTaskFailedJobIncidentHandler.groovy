package org.oxzion.processengine

import org.camunda.bpm.engine.impl.TaskServiceImpl
import org.camunda.bpm.engine.impl.incident.DefaultIncidentHandler
import org.camunda.bpm.engine.impl.incident.IncidentContext
import org.camunda.bpm.engine.impl.incident.IncidentHandler
import org.camunda.bpm.engine.impl.interceptor.CommandExecutorImpl
import org.camunda.bpm.engine.runtime.Incident
import org.camunda.bpm.engine.task.Task
import org.slf4j.Logger
import org.slf4j.LoggerFactory

class UserTaskFailedJobIncidentHandler extends DefaultIncidentHandler implements IncidentHandler {

    private static final Logger logger = LoggerFactory.getLogger(UserTaskFailedJobIncidentHandler.class)

    UserTaskFailedJobIncidentHandler(String type) {
        super(type)
    }

    String getIncidentHandlerType() {
        return "ServiceTaskFailure"
    }

    @Override
    Incident handleIncident(IncidentContext context, String message) {
        Incident incident = handleIncident(context,message)
        println(incident.toString())
        TaskServiceImpl taskServiceImpl = getTaskService()
        Task task = taskServiceImpl.newTask()
        task.setName("Handle Incident")
        taskServiceImpl.saveTask(task)
        taskServiceImpl.setVariable(task.getId(), "executionId", incident.getExecutionId())
    }

    private static TaskServiceImpl getTaskService() {
        TaskServiceImpl taskServiceImpl = new TaskServiceImpl()
        taskServiceImpl.setCommandExecutor(new CommandExecutorImpl())
        return taskServiceImpl
    }

    @Override
    void resolveIncident(IncidentContext context) {
        super.resolveIncident(context)
    }

    @Override
    void deleteIncident(IncidentContext context) {
        super.deleteIncident(context)
    }
}

