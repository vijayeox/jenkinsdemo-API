package com.oxzion.routes

import groovy.json.JsonBuilder
import groovy.json.JsonSlurper
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.component.properties.PropertiesComponent
import org.apache.camel.impl.DefaultCamelContext
import org.springframework.boot.context.properties.EnableConfigurationProperties
import org.springframework.stereotype.Component
import org.apache.activemq.command.ActiveMQTextMessage

import javax.activation.DataHandler
import javax.activation.FileDataSource
import org.slf4j.Logger
import org.slf4j.LoggerFactory

/**
 * Client for sending mails over smtp.
 * @author Bharat Gogineni
 *
 */
@Component
@EnableConfigurationProperties

public class SendSmtpMail extends RouteBuilder {
    private static final Logger logger = LoggerFactory.getLogger(SendSmtpMail.class);
    @Override
    void configure() throws Exception {
        CamelContext context = new DefaultCamelContext()
        PropertiesComponent pc = getContext().getComponent("properties", PropertiesComponent.class)
        context.addComponent("properties", pc)
        context.addRoutes(new RouteBuilder() {
            @Override
            public void configure() {
                from("activemq:queue:mail").doTry().process(new Processor() {
                    public void process(Exchange exchange) throws Exception {
                        def jsonSlurper = new JsonSlurper()
                        def messageIn  = exchange.getIn()
                        def object = jsonSlurper.parseText(exchange.getMessage().getBody() as String)
                        logger.info("Processing Email with payload ${object}")
                    
                        if(object.to){
                            def toList = ""
                            toList = setMessageHeader(object.to)
                            messageIn.setHeader("To", toList)
                        }
                        if(object.cc){
                            def ccList = ""
                            ccList = setMessageHeader(object.cc)
                            messageIn.setHeader("Cc", ccList)
                        }

                        def smtpBccList = ""
                        try {
                            smtpBccList = getContext().resolvePropertyPlaceholders("{{smtp.bcc.email}}")
                        } catch(Exception ex) {
                            smtpBccList = ""
                        }
                        def bccList = ""
                        if(object.bcc){
                            bccList = setMessageHeader(object.bcc)
                            if(smtpBccList){
                                smtpBccList += ","+bccList    
                            }else{
                                smtpBccList += bccList
                            }
                        }
                        if(smtpBccList){
                             messageIn.setHeader("Bcc", smtpBccList)    
                        }
                       
                        if(object.from){
                            messageIn.setHeader("From", object.from as String)
                        } else {
                            messageIn.setHeader("From", getContext().resolvePropertyPlaceholders("{{smtp.from.email}}"))
                        }
                        if(object.subject){
                            messageIn.setHeader("Subject", object.subject as String)
                        } else {
                            messageIn.setHeader("Subject", getContext().resolvePropertyPlaceholders("{{default.subject}}"))
                        }
                        if(object.body){
                            messageIn.setBody(object.body as String)
                        } else {
                            messageIn.setBody('')
                        }
                        messageIn.setHeader("Content-Type", "text/html")
                        logger.info("Mail Headers" + messageIn.getHeaders() as String)
                        if(object.attachments){
                            if(object.attachments.size()>0){
                                def attachmentList = object.attachments as ArrayList
                                for (int i=0;i<attachmentList.size();i++){
                                    def fileLocation = new File(attachmentList.get(i) as String)
                                    def fileName = fileLocation.getAbsolutePath().substring(fileLocation.getAbsolutePath().lastIndexOf("/")+1)
                                    messageIn.addAttachment(fileName, new DataHandler(new FileDataSource(fileLocation)))
                                }
                            }
                        }
                    }
                }).log("Received body ").to("smtp://{{smtp.host}}?mail.smtp.auth=false").doCatch(Exception.class).process(new Processor() {
                    void process(Exchange exchange) throws Exception {
                        Exception exception = (Exception) exchange.getProperty(Exchange.EXCEPTION_CAUGHT)
                        def params = [to: 'mail']
                        def jsonparams = new JsonBuilder(params).toPrettyString()
                        def stackTrace = new JsonBuilder(exception).toPrettyString()
                        logger.info("Processing Email with payload ${exchange.getMessage().getJmsMessage()}")
                        def message = exchange.getMessage().getJmsMessage();
                        if (message instanceof ActiveMQTextMessage) {
                            ActiveMQTextMessage textMessage = (ActiveMQTextMessage) message;
                            try {
                                String json = message.getText();
                                logger.info(json)
                                ErrorLog.log('activemq_queue',stackTrace,json,jsonparams)
                            } catch (Exception e) {
                                logger.info("Could not extract data to log from TextMessage - ${e}")
                            }
                        }
                    }
                })
            }
        })
        context.start()
    }

    def setMessageHeader(header){
        def list = ""
        def recepientList = header instanceof String ? [header] : header as ArrayList
        for (int i=0;i<recepientList.size();i++){
            def recepient = recepientList.get(i)
            if(i<recepientList.size()-1){
                list += recepient+","
            } else {
                list += recepient
            }
        }
       return list
    }
}
