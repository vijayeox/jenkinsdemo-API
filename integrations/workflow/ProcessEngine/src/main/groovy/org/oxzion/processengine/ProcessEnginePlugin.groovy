package org.oxzion.processengine

import org.camunda.bpm.engine.impl.bpmn.parser.BpmnParseListener
import org.camunda.bpm.engine.impl.cfg.AbstractProcessEnginePlugin
import org.camunda.bpm.engine.impl.cfg.ProcessEngineConfigurationImpl

class ProcessEnginePlugin extends AbstractProcessEnginePlugin {
    @Override
    void preInit(ProcessEngineConfigurationImpl processEngineConfiguration) {
        List<BpmnParseListener> preParseListeners = processEngineConfiguration.getCustomPreBPMNParseListeners()
        if(preParseListeners == null) {
            preParseListeners = new ArrayList<BpmnParseListener>()
            processEngineConfiguration.setCustomPreBPMNParseListeners(preParseListeners)
        }
        preParseListeners.add(new CustomProcessEngine())
    }
}
