package org.oxzion.camunda
import org.camunda.bpm.engine.authorization.Groups

class Group implements org.camunda.bpm.engine.identity.Group{
    private String groupId
    private String name
    private String type

    Group(groupId,name,type){
        this.groupId = groupId
        this.name = name
        this.type = type
    }
    @Override
    String getId() {
        return this.groupId
    }

    @Override
    void setId(String id) {
        this.groupId = id
    }

    @Override
    String getName() {
        return this.name
    }

    @Override
    void setName(String name) {
        this.name = name
    }

    @Override
    String getType() {
        return this.type
    }

    @Override
    void setType(String string) {
        this.type = string
    }
}
