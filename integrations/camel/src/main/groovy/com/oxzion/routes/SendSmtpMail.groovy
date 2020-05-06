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
        pc.setLocation("classpath:mail.properties")
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
                        def toList = ""
                        if(object.to){
                            def recepientList = object.to instanceof String ? [object.to] : object.to as ArrayList
                            for (int i=0;i<recepientList.size();i++){
                                def recepient = recepientList.get(i)
                                if(i<recepientList.size()-1){
                                    toList += recepient+","
                                } else {
                                    toList += recepient
                                }
                            }
                            messageIn.setHeader("To", toList)
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
                        ErrorLog.log('activemq_queue',stackTrace,exchange.getMessage().getBody().toString(),jsonparams)
                        System.out.println("handling ex")
                    }
                })
            }
        })
        context.start()
    }
}
