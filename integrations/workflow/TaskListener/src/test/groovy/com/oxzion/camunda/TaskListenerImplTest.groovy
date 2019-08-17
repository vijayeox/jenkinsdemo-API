package com.oxzion.camunda

import org.junit.Test
import static org.junit.Assert.*
import static org.junit.Assume.*

public class TaskListenerImplTest {
    @Test
    public void testTaskListener() {
        TaskListenerImpl loader = new TaskListenerImpl()
        def connection = loader.getConnection()
        // println(connection.callback.URL)
        assertNotNull(connection) 
           
    }
}