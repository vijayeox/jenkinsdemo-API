package org.oxzion.camunda

class Tenant implements org.camunda.bpm.engine.identity.Tenant {

    private String tenantId
    private String name

    Tenant(tenantId,name){
        this.tenantId = tenantId
        this.name = name
    }
    @Override
    String getId() {
        return this.tenantId
    }

    @Override
    void setId(String id) {
        this.tenantId = id
    }

    @Override
    String getName() {
        return name
    }

    @Override
    void setName(String name) {
        this.name = name
    }
}
