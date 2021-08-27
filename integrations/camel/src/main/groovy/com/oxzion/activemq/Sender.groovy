package com.oxzion.activemq

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.jms.core.JmsTemplate;

public class Sender {

    private static final Logger LOGGER =
            LoggerFactory.getLogger(Sender.class);

    @Autowired
    private JmsTemplate jmsTemplate;

    public void send(String message,String topic) {
        LOGGER.info("sending message='{}'", message);
        jmsTemplate.convertAndSend(topic, message);
    }
}