package org.oxzion.camunda

import org.camunda.bpm.engine.ProcessEngine
import org.camunda.bpm.engine.impl.cfg.ProcessEngineConfigurationImpl
import org.camunda.bpm.engine.impl.cfg.ProcessEnginePlugin

class IdentityProviderPlugin implements ProcessEnginePlugin  {
    @Override
    void preInit(ProcessEngineConfigurationImpl processEngineConfiguration) {
        IdentityProviderFactory identityProviderFactory = new IdentityProviderFactory()
        processEngineConfiguration.setIdentityProviderSessionFactory(identityProviderFactory)
    }

    @Override
    void postInit(ProcessEngineConfigurationImpl processEngineConfiguration) {

    }

    @Override
    void postProcessEngineBuild(ProcessEngine processEngine) {

    }
}
