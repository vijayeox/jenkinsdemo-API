package com.camunda

import org.camunda.bpm.engine.identity.Group
import org.camunda.bpm.engine.identity.GroupQuery
import org.camunda.bpm.engine.identity.NativeUserQuery
import org.camunda.bpm.engine.identity.Tenant
import org.camunda.bpm.engine.identity.TenantQuery
import org.camunda.bpm.engine.identity.User
import org.camunda.bpm.engine.identity.UserQuery
import org.camunda.bpm.engine.impl.identity.ReadOnlyIdentityProvider
import org.camunda.bpm.engine.impl.interceptor.CommandContext
import groovy.sql.Sql

class IdentityService implements ReadOnlyIdentityProvider {
    private sql

    IdentityService(){
        this.sql = Sql.newInstance("jdbc:mysql://172.16.1:3306/oxapi", "root","", "com.mysql.jdbc.Driver")
    }
    @Override
    User findUserById(String userId) {
        def row = this.sql.firstRow('select id,firstname,lastname,email,password from users where id = "'+userId+'"')
        return new com.camunda.User(row.id,row.email,row.firstname,row.lastname,row.password)
    }

    @Override
    UserQuery createUserQuery() {
        return null
    }

    @Override
    UserQuery createUserQuery(CommandContext commandContext) {
        return null
    }

    @Override
    NativeUserQuery createNativeUserQuery() {
        return null
    }

    @Override
    boolean checkPassword(String userId, String password) {
        return false
    }

    @Override
    Group findGroupById(String groupId) {
        def row = this.sql.firstRow('select id,name,type from ox_groups where id = "'+groupId+'"')
        return new com.camunda.Group(row.id,row.name,row.type)
    }

    @Override
    GroupQuery createGroupQuery() {
        return null
    }

    @Override
    GroupQuery createGroupQuery(CommandContext commandContext) {
        return null
    }

    @Override
    Tenant findTenantById(String tenantId) {
        def row = this.sql.firstRow('select id,name from ox_organization where id = "'+tenantId+'"')
        return new com.camunda.Tenant(row.id,row.name)
    }

    @Override
    TenantQuery createTenantQuery() {
        return null
    }

    @Override
    TenantQuery createTenantQuery(CommandContext commandContext) {
        return null
    }

    @Override
    void flush() {

    }

    @Override
    void close() {

    }
}
