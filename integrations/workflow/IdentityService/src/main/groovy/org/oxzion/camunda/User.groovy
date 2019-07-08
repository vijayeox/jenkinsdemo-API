package org.oxzion.camunda

class User implements org.camunda.bpm.engine.identity.User {
    private String userId
    private String email
    private String firstName
    private String lastName
    private String password

    User(userId,email,firstName,lastName,password){
        this.userId = userId
        this.email = email
        this.firstName = firstName
        this.lastName = lastName
        this.password = password
    }
    @Override
    String getId() {
        return this.userId
    }

    @Override
    void setId(String id) {
        this.userId = id
    }

    @Override
    String getFirstName() {
        return this.firstName
    }

    @Override
    void setFirstName(String firstName) {
        this.firstName = firstName
    }

    @Override
    void setLastName(String lastName) {
        this.lastName = lastName
    }

    @Override
    String getLastName() {
        return this.lastName
    }

    @Override
    void setEmail(String email) {
        this.email = email
    }

    @Override
    String getEmail() {
        return this.email
    }

    @Override
    String getPassword() {
        return this.password
    }

    @Override
    void setPassword(String password) {
        this.password = password
    }
}
