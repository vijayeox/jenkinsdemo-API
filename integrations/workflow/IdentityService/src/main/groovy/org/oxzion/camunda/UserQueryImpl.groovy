package org.oxzion.camunda

import org.camunda.bpm.engine.impl.Page
import org.camunda.bpm.engine.impl.interceptor.CommandContext
import org.camunda.bpm.engine.impl.interceptor.CommandExecutor


class UserQueryImpl extends org.camunda.bpm.engine.impl.UserQueryImpl {
    UserQueryImpl() {
        super()
    }

    UserQueryImpl(CommandExecutor commandExecutor) {
        super(commandExecutor)
    }
    @Override
    long executeCount(CommandContext commandContext) {
        final IdentityProvider provider = getCustomIdentityProvider(commandContext)
        return provider.findUserCountByQueryCriteria(this)
    }

    @Override
    List<org.camunda.bpm.engine.identity.User> executeList(CommandContext commandContext, Page page) {
        final IdentityProvider provider = getCustomIdentityProvider(commandContext)
        return provider.findUserByQueryCriteria(this)
    }
    protected static IdentityProvider getCustomIdentityProvider(CommandContext commandContext) {
        return (IdentityProvider) commandContext.getReadOnlyIdentityProvider()
    }
}
