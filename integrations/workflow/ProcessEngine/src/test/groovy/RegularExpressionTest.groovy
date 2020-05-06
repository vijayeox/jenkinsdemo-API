package com.oxzion.routes

import org.junit.Test
import static org.junit.Assert.*
import static org.junit.Assume.*

public class RegularExpressionTest {
    @Test
    public void testRoutes() {
    println "Test Data"
    def reg = /\{\{[A-Za-z0-9]*\}\}/
    def assignee = '{{padi}}'
    println "Task Assignee - ${assignee}"
    println "Task Assignee match - ${assignee ==~ reg}"
    if(assignee ==~ reg){
      println "TaskAssigneeList"
      def val = assignee.substring(2, assignee.length()-2)
      assignee = val
      println "Task Assignee List End - ${val}"
    }
    println "Task Assignee - ${assignee}"
    }
}