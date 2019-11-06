package com.oxzion.routes

import groovy.json.JsonBuilder
import groovy.json.JsonOutput
import groovy.sql.*
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.impl.DefaultCamelContext
import org.springframework.stereotype.Component

import java.util.Date
import java.text.SimpleDateFormat

@Component
class RoutesLoader extends RouteBuilder{
    @Override
    void configure() throws Exception {
        println "In RoutesBuilder"
        def routes = loadRoutes()
        routes = routes.routes
        CamelContext context = new DefaultCamelContext()
        context.addRoutes(new RouteBuilder() {
            @Override
            void configure() {
                println routes
                routes.route.each{route->
                    def definition = from(route.from)
                    .process(new Processor() {
                    void process(Exchange exchange) throws Exception {
                        exchange.getIn().setBody(exchange.getMessage().getBody())
                        exchange.setProperty(Exchange.CHARSET_NAME, "UTF-8")
                        exchange.getIn().setHeader(Exchange.CONTENT_TYPE, "application/x-www-form-urlencoded")
                        exchange.getIn().setHeader(Exchange.HTTP_METHOD, "POST")
                        println("payload - ${exchange.getMessage().getBody()}")
                    }
                }).doTry()
                // .setHeader(Exchange.CONTENT_TYPE, constant("application/json")).setHeader(Exchange.HTTP_METHOD, constant("POST"))
                    // .process(new Processor() {
                    //     public void process(Exchange exchange) throws Exception {
                    //         exchange.getIn().setHeader(Exchange.CONTENT_TYPE, "text/html")
                    //         exchange.getIn().setHeader(Exchange.HTTP_METHOD, "POST")
                    //         exchange.getIn().setBody(exchange.getMessage().getBody() as String)
                    //         println("payload - ${exchange.getMessage().getBody()}")
                    //     }
                    // })
                    // .setHeader(Exchange.CONTENT_TYPE, constant("application/json"))
                    // .setHeader(Exchange.HTTP_METHOD,constant("POST"))
                   // .to("log:DEBUG?showBody=true&showHeaders=true")
                    route.to.each{
                        try{
                            definition.to(it).to("log:DEBUG?showBody=true&showHeaders=true").doCatch(Exception.class).process(new Processor() {

                                void process(Exchange exchange) throws Exception {
                                    Exception exception = (Exception) exchange.getProperty(Exchange.EXCEPTION_CAUGHT)
                                    Date now = new Date()
                                    String pattern = "yyyy-MM-dd HH:mm:ss"
                                    SimpleDateFormat formatter = new SimpleDateFormat(pattern)
                                    String mysqlDateString = formatter.format(now)
                                    def params = [to: it]
                                    def jsonparams = new JsonBuilder(params).toPrettyString()
                                    def stackTrace = new JsonBuilder(exception).toPrettyString()
                                    def sql = Sql.newInstance(System.getenv('DB_HOST'), System.getenv('DB_USER'), System.getenv('DB_PASS'), "com.mysql.jdbc.Driver")
                                    // insert data
                                    sql.execute("INSERT INTO ox_error_log (error_type, error_trace,payload,date_created,params) values ('activemq', ${stackTrace},${exchange.getMessage().getBody()},${mysqlDateString}, ${jsonparams})")
                                    // close connection
                                    sql.close()
                                    System.out.println("handling ex")
                                }
                            }).log("Received body ").handled(true)
                        }catch(Exception e){
                            println "Error when invoking ${it} - ${e.printStackTrace()}"
                        }
                    }
                }
            }
        })
        context.start()
    }

    
    def loadRoutes(){
        String routeLocation = System.getProperty('ROUTE_CONFIG')
        println "routeLocation - ${routeLocation}"
        URL url
        if(routeLocation){
            routeLocation = "${routeLocation}/Routes.groovy"
            File routeFile = new File(routeLocation)
            println "file - ${routeFile}"
            println "routeFileExists - ${routeFile.exists()}"
            if(routeFile.exists()){
                url = new URL("file:///${routeFile.absolutePath}")
            }
        }
        println "url - ${url}"
        if(!url){
            url = RoutesLoader.class.classLoader.getResource("Routes.groovy")
            
        }
        println url
        return new ConfigSlurper().parse(url)

    }
}