package org.oxzion.messaging

import groovy.sql.Sql
import org.junit.Test
import org.mockito.Mock
import org.mockito.Mockito
import org.oxzion.processengine.ErrorHandler

import javax.jms.JMSException
import org.junit.jupiter.api.BeforeEach;
import org.mockito.MockitoAnnotations
import static org.mockito.ArgumentMatchers.anyString
import static org.mockito.ArgumentMatchers.isA;
import static org.mockito.Mockito.doAnswer
import static org.mockito.Mockito.doNothing;
import static org.mockito.Mockito.doThrow;
import static org.mockito.Mockito.mock
import static org.mockito.Mockito.times;
import static org.mockito.Mockito.when;

import static org.junit.Assert.*
import static org.junit.Assume.*

class ActiveMQTest {

    @Test
    public void testEmail() {
        Publisher publisher1 = mock(Publisher.class)
        ErrorHandler.publisher = publisher1
        doNothing().when(publisher1).sendQueue(isA(String.class),isA(String.class))
        ErrorHandler.log('activemq_error','trace','{some json string}','a=1','d77ea120-b028-479b-8c6e-60476b6a4456')
        Mockito.verify(publisher1,times(1)).sendQueue("{\"subject\":\"Error occurrence\",\"to\":\"brianmp@eoxvantage.in\",\"body\":\"{\\\"payload\\\":\\\"{some json string}\\\",\\\"error_type\\\":\\\"activemq_error\\\",\\\"error_trace\\\":\\\"trace\\\",\\\"params\\\":\\\"a=1\\\"}\"}",'mail')
    }

    @Test
    public void testDBSave(){
        int id = ErrorHandler.log("test_error",'trace','{some json string}','a=2','d77ea120-b028-479b-8c6e-60476b6a4456')
        def prop = ErrorHandler.getConfig()
        def API_DB_URL = prop."api_db_url"
        def DB_DRIVER = prop."db_driver"
        def DB_USERNAME = prop."db_username"
        def DB_PASSWORD = prop."db_password"
        def sql = Sql.newInstance(API_DB_URL, DB_USERNAME, DB_PASSWORD, DB_DRIVER)
        def data = sql.firstRow("SELECT * FROM ox_error_log where id=?",[id])
        assertEquals("test_error",data.error_type)
        assertEquals("trace",data.error_trace)
        assertEquals("a=2",data.params)

    }
}
