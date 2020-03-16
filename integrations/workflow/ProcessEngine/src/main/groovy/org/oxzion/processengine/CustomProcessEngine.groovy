package org.oxzion.processengine


import org.camunda.bpm.engine.impl.bpmn.behavior.UserTaskActivityBehavior
import org.camunda.bpm.engine.impl.bpmn.parser.AbstractBpmnParseListener
import org.camunda.bpm.engine.impl.pvm.delegate.ActivityBehavior
import org.camunda.bpm.engine.impl.pvm.process.ActivityImpl
import org.camunda.bpm.engine.impl.pvm.process.ScopeImpl
import org.camunda.bpm.engine.impl.util.xml.Element

import java.util.logging.Logger

class CustomProcessEngine extends AbstractBpmnParseListener {
  static final long serialVersionUID = -757671492884005066L
  private final Logger LOGGER = Logger.getLogger(this.getClass().getName())
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
  void parseStartEvent(Element startEventElement, ScopeImpl scope, ActivityImpl activity) {
    ActivityBehavior activityBehavior = activity.getActivityBehavior()
    activity.addListener("start",EventListener.getInstance("start"))
  }
  @Override
  void parseEndEvent(Element endEventElement, ScopeImpl scope, ActivityImpl activity) {
    ActivityBehavior activityBehavior = activity.getActivityBehavior()
    activity.addListener("end",EventListener.getInstance("end"))
  }
  @Override
  void parseServiceTask(Element serviceTaskElement, ScopeImpl scope, ActivityImpl activity) {
    Element extensionElement = serviceTaskElement.element("extensionElements")
    if (extensionElement != null) {
      Element executionListener = extensionElement.element("executionListener")
      CustomServiceTaskListener serviceTaskListener = new CustomServiceTaskListener()
      activity.addListener("start",serviceTaskListener)
    }
  }
}
