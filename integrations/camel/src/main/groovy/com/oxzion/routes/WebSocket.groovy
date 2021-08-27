package com.oxzion.routes

import groovy.json.JsonSlurper
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.component.properties.PropertiesComponent
import org.apache.camel.impl.DefaultCamelContext
import org.springframework.stereotype.Component

@Component
class WebSocket extends RouteBuilder {
    @Override
    void configure() throws Exception {
        CamelContext context = new DefaultCamelContext()
        context.addRoutes(new RouteBuilder() {
            @Override
            public void configure() {
                PropertiesComponent propc = getContext().getComponent("properties", PropertiesComponent.class)
                propc.setLocation("classpath:oxzion.properties")
                from("activemq:topic:notification").process(new Processor() {
                    public void process(Exchange exchange) throws Exception {
                        def jsonSlurper = new JsonSlurper()
                        def object = jsonSlurper.parseText(exchange.getMessage().getBody() as String)
                    }
                }).to("log:notification").to("ahc-ws://{{websocket.host}}:{{websocket.port}}/{{websocket.channel}}")

            }
        })
        context.start()
    }
}
