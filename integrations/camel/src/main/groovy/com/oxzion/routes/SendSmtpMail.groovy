package com.oxzion.routes

import groovy.json.JsonBuilder
import groovy.json.JsonSlurper
import groovy.sql.*
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.component.properties.PropertiesComponent
import org.apache.camel.impl.DefaultCamelContext
import org.springframework.stereotype.Component

import javax.activation.DataHandler
import javax.activation.FileDataSource
import java.text.SimpleDateFormat

/**
 * Client for sending mails over smtp.
 * @author Bharat Gogineni
 *
 */
@Component
public class SendSmtpMail extends RouteBuilder {
    @Override
    void configure() throws Exception {
        CamelContext context = new DefaultCamelContext()
        context.addRoutes(new RouteBuilder() {
            @Override
            public void configure() {
                PropertiesComponent pc = getContext().getComponent("properties", PropertiesComponent.class)
                pc.setLocation("classpath:mail.properties")
                from("activemq:queue:mail").doTry().process(new Processor() {
                    public void process(Exchange exchange) throws Exception {
                        def jsonSlurper = new JsonSlurper()
                        def object = jsonSlurper.parseText(exchange.getMessage().getBody() as String)
                        def toList = ""
                        if(object.to){
                            def recepientList = object.to instanceof String ? [object.to] : object.to as ArrayList
                            for (int i=0;i<recepientList.size();i++){
                                def recepient = recepientList.get(i)
                                if(recepientList.size()>1){
                                    toList += recepient+","
                                } else {
                                    toList += recepient
                                }
                            }
                            exchange.getIn().setHeader("To", toList)
                        }
                        if(object.from){
                            exchange.getIn().setHeader("From", object.from as String)
                        } else {
                            exchange.getIn().setHeader("From", getContext().resolvePropertyPlaceholders("{{smtp.from.email}}"))
                        }
                        if(object.subject){
                            exchange.getIn().setHeader("Subject", object.subject as String)
                        } else {
                            exchange.getIn().setHeader("Subject", getContext().resolvePropertyPlaceholders("{{default.subject}}"))
                        }
                        exchange.getIn().setBody(object.body as String)
                         
                        exchange.getIn().setHeader("Content-Type", "text/html")
                        if(object.attachments){
                            if(object.attachments.size()>0){
                                def attachmentList = object.attachments as ArrayList
                                for (int i=0;i<attachmentList.size();i++){
                                    def fileLocation = new File(attachmentList.get(i) as String)
                                    def fileName = fileLocation.getAbsolutePath().substring(fileLocation.getAbsolutePath().lastIndexOf("\\")+1)
                                    exchange.getIn().addAttachment(fileName, new DataHandler(new FileDataSource(fileLocation)))
                                }
                                
                            }
                        }
                        
                    }
                }).doCatch(Exception.class).process(new Processor() {
                    void process(Exchange exchange) throws Exception {
                        Exception exception = (Exception) exchange.getProperty(Exchange.EXCEPTION_CAUGHT)
                        Date now = new Date()
                        String pattern = "yyyy-MM-dd HH:mm:ss"
                        def params = [to: 'mail']
                        SimpleDateFormat formatter = new SimpleDateFormat(pattern)
                        String mysqlDateString = formatter.format(now)
                        def jsonparams = new JsonBuilder(params).toPrettyString()
                        def stackTrace = new JsonBuilder(exception).toPrettyString()
                        def sql = Sql.newInstance(System.getenv('DB_HOST'), System.getenv('DB_USER'), System.getenv('DB_PASS'), "com.mysql.jdbc.Driver")
                        // insert data
                        sql.execute("INSERT INTO ox_error_log (error_type, error_trace,payload,date_created,params) values ('activemq', ${stackTrace},${exchange.getMessage().getBody()},${mysqlDateString}, ${jsonparams})")
                        // close connection
                        sql.close()
                        System.out.println("handling ex")
                    }
                }).log("Received body ").handled(true).to("smtp://"+System.getenv('SMTP_HOST')+"?username="+System.getenv('SMTP_HOST')+"&password="+System.getenv('SMTP_HOST')+")")
            }
        })
        context.start()
    }
}
