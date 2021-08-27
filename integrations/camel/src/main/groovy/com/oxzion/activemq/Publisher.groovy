package com.oxzion.activemq

import javax.jms.Connection;
import javax.jms.ConnectionFactory;
import javax.jms.JMSException;
import javax.jms.MessageProducer;
import javax.jms.Session;
import javax.jms.TextMessage;
import javax.jms.Topic;

import org.apache.activemq.ActiveMQConnection;
import org.apache.activemq.ActiveMQConnectionFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value
import org.springframework.core.env.Environment;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class Publisher {

    @Autowired
    private Environment env

    @Value('${spring.activemq.broker-url}')
    private String brokerUrl

    private static final Logger LOGGER =
            LoggerFactory.getLogger(Publisher.class);

    private String clientId;
    private Connection connection;
    private Session session;
    private MessageProducer messageProducer;

    public void create(String clientId, String topicName)
            throws JMSException {
        this.clientId = clientId;

        // create a Connection Factory
        ConnectionFactory connectionFactory =
                new ActiveMQConnectionFactory();
        activeMQConnectionFactory.setBrokerURL(brokerUrl)
        // create a Connection
        connection = connectionFactory.createConnection();
        connection.setClientID(clientId);

        // create a Session
        session =
                connection.createSession(false, Session.AUTO_ACKNOWLEDGE);

        // create the Topic to which messages will be sent
        Topic topic = session.createTopic(topicName);

        // create a MessageProducer for sending messages
        messageProducer = session.createProducer(topic);
    }

    public void closeConnection() throws JMSException {
        connection.close();
    }

    public void sendData(String Data)
            throws JMSException {

        // create a JMS TextMessage
        TextMessage textMessage = session.createTextMessage(Data)

        // send the message to the topic destination
        messageProducer.send(textMessage)

        LOGGER.debug(clientId + ": sent message with text='{}'", Data)
    }
}