package org.oxzion.processengine

import org.camunda.bpm.engine.impl.bpmn.behavior.NoneEndEventActivityBehavior
import org.camunda.bpm.engine.impl.bpmn.behavior.UserTaskActivityBehavior
import org.camunda.bpm.engine.impl.bpmn.parser.AbstractBpmnParseListener
import org.camunda.bpm.engine.impl.pvm.delegate.ActivityBehavior
import org.camunda.bpm.engine.impl.pvm.process.ActivityImpl
import org.camunda.bpm.engine.impl.pvm.process.ScopeImpl
import org.camunda.bpm.engine.delegate.ExecutionListener
import org.camunda.bpm.engine.impl.util.xml.Element

import java.util.logging.Logger

class CustomProcessEngine extends AbstractBpmnParseListener {
  private final Logger LOGGER = Logger.getLogger(this.getClass().getName());
     @Override
  void parseUserTask(Element userTaskElement, ScopeImpl scope, ActivityImpl activity) {
    ActivityBehavior activityBehavior = activity.getActivityBehavior()
    if(activityBehavior instanceof UserTaskActivityBehavior ){
      UserTaskActivityBehavior userTaskActivityBehavior = (UserTaskActivityBehavior) activityBehavior
      userTaskActivityBehavior.getTaskDefinition().addTaskListener("create", CustomTaskListener.getInstance())
      userTaskActivityBehavior.getTaskDefinition().addTaskListener("complete", CustomTaskCompleteListener.getInstance())
    }
  }
  @Override
  void parseEndEvent(Element endEventElement, ScopeImpl scope, ActivityImpl activity) {
    ActivityBehavior activityBehavior = activity.getActivityBehavior()
    activity.addListener("end",EndEventListener.getInstance())
  }
  @Override
  void parseServiceTask(Element serviceTaskElement, ScopeImpl scope, ActivityImpl activity) {
    Element extensionElement = serviceTaskElement.element("extensionElements")
    if (extensionElement != null) {
      Element inputOutElement = extensionElement.element("inputOutput")
      if (inputOutElement != null) {
        List<Element> inputParameters = inputOutElement.elements("inputParameter")
        for (Element inputParameter : inputParameters) {
            CustomServiceTaskListener serviceTaskListener = new CustomServiceTaskListener()
            activity.addListener("start",serviceTaskListener)
          }
      }
    }
  }
}
