package com.oxzion.routes

import groovy.json.JsonBuilder
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.impl.DefaultCamelContext
import org.springframework.stereotype.Component


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
                    route.to.each{
                        try{
                            definition.to(it).to("log:DEBUG?showBody=true&showHeaders=true").doCatch(Exception.class).process(new Processor() {
                                void process(Exchange exchange) throws Exception {
                                    Exception exception = (Exception) exchange.getProperty(Exchange.EXCEPTION_CAUGHT)
                                    def params = [to: it]
                                    def jsonparams = new JsonBuilder(params).toPrettyString()
                                    def stackTrace = new JsonBuilder(exception).toPrettyString()
                                    ErrorLog.log('activemq_topic',stackTrace,exchange.getMessage().getBody().toString(),jsonparams);
                                    System.out.println("handling ex")
                                }
                            }).log("Received body ")
                        }catch(Exception e){
                            println "Error when invoking ${it} - ${e.printStackTrace()}"
                        }
                    }
                }
            }
        })
        context.start()
    }

    //flag is added for test : Junit
    def loadRoutes(String flag = null){
        String routeLocation = System.getProperty('ROUTE_CONFIG')
        println "routeLocation - ${routeLocation}"
        URL url
        if(routeLocation){
            routeLocation = "${routeLocation}/Routes.groovy"
            File routeFile = new File(routeLocation)
            println "file - ${routeFile}"
            println "routeFileExists - ${routeFile.exists()}"
            println "absolutePath - ${routeFile.absolutePath}"
            if(routeFile.exists()){
                url = new URL("file:${routeFile.absolutePath}")
            }
        }else{
            //load the routes from Externalise route file
            url = RoutesLoader.class.classLoader.getResource("Routes.groovy")
        }
        println "url - ${url}"
        return new ConfigSlurper().parse(url)

    }
}