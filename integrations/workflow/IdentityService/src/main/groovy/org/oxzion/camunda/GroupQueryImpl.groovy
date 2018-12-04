package org.oxzion.camunda

import org.camunda.bpm.engine.impl.Page
import org.camunda.bpm.engine.impl.interceptor.CommandContext
import org.camunda.bpm.engine.impl.interceptor.CommandExecutor

class GroupQueryImpl extends org.camunda.bpm.engine.impl.GroupQueryImpl {
    GroupQueryImpl() {
        super()
    }

    GroupQueryImpl(CommandExecutor commandExecutor) {
        super(commandExecutor)
    }
    @Override
    long executeCount(CommandContext commandContext) {
        final IdentityProvider provider = getCustomIdentityProvider(commandContext)
        return provider.findGroupCountByQueryCriteria(this)
    }

    @Override
    List<org.camunda.bpm.engine.identity.Group> executeList(CommandContext commandContext, Page page) {
        final IdentityProvider provider = getCustomIdentityProvider(commandContext);
        return provider.findGroupByQueryCriteria(this);
    }
    protected IdentityProvider getCustomIdentityProvider(CommandContext commandContext) {
        return (IdentityProvider) commandContext.getReadOnlyIdentityProvider()
    }
}
