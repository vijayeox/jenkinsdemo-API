package org.oxzion.messaging

import javax.jms.Connection
import javax.jms.ConnectionFactory
import javax.jms.JMSException
import javax.jms.MessageProducer
import javax.jms.Session
import javax.jms.TextMessage
import javax.jms.Topic;
import javax.jms.Queue

import org.apache.activemq.ActiveMQConnection
import org.apache.activemq.ActiveMQConnectionFactory
import org.slf4j.Logger
import org.slf4j.LoggerFactory

public class Publisher {

    private static final Logger LOGGER =
    LoggerFactory.getLogger(Publisher.class)

    private Connection connection

    private static Publisher obj
    private Publisher(){
       create()
   }

   static Publisher getPublisher(){
       if(!obj) {
           obj = new Publisher()
       }
       return obj
   }

   private void create()
   throws JMSException {

        // create a Connection Factory
        ConnectionFactory connectionFactory =
        new ActiveMQConnectionFactory(
            ActiveMQConnection.DEFAULT_BROKER_URL)

        // create a Connection
        connection = connectionFactory.createConnection()

    }

    public void closeConnection() throws JMSException {
        connection.close()
    }

    public void sendQueue(String text,String queueName)
    throws JMSException {
        Session session
        try {

            session = connection.createSession(false, Session.AUTO_ACKNOWLEDGE)
            Queue queue = session.createQueue(queueName)
            MessageProducer messageProducer = session.createProducer(queue)

            // create a JMS TextMessage
            TextMessage textMessage = session.createTextMessage(text)

            // send the message to the topic destination
            messageProducer.send(textMessage)

            LOGGER.debug("sent message with text='{}'", text)
        } finally {
            session?.close()
        }
    }
}
