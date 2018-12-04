package org.oxzion.camunda

import org.camunda.bpm.engine.impl.identity.ReadOnlyIdentityProvider
import org.camunda.bpm.engine.impl.interceptor.Session
import org.camunda.bpm.engine.impl.interceptor.SessionFactory

class IdentityProviderFactory implements SessionFactory{
    @Override
    Class<?> getSessionType() {
        return ReadOnlyIdentityProvider.class
    }

    @Override
    Session openSession() {
        return new IdentityProvider()
    }
}
