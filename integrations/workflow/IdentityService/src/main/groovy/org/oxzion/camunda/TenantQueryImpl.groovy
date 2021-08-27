package org.oxzion.camunda


import org.camunda.bpm.engine.impl.Page
import org.camunda.bpm.engine.impl.interceptor.CommandContext
import org.camunda.bpm.engine.impl.interceptor.CommandExecutor

class TenantQueryImpl extends org.camunda.bpm.engine.impl.TenantQueryImpl {
    TenantQueryImpl() {
        super()
    }

    TenantQueryImpl(CommandExecutor commandExecutor) {
        super(commandExecutor)
    }
    @Override
    long executeCount(CommandContext commandContext) {
        final IdentityProvider provider = getCustomIdentityProvider(commandContext)
        return provider.findTenantCountByQueryCriteria(this)
    }

    @Override
    List<org.camunda.bpm.engine.identity.Tenant> executeList(CommandContext commandContext, Page page) {
        final IdentityProvider provider = getCustomIdentityProvider(commandContext)
        return provider.findTenantByQueryCriteria(this);
    }
    protected static IdentityProvider getCustomIdentityProvider(CommandContext commandContext) {
        return (IdentityProvider) commandContext.getReadOnlyIdentityProvider()
    }
}
