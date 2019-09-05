package com.oxzion.routes

import groovy.json.JsonSlurper
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.component.properties.PropertiesComponent
import org.apache.camel.impl.DefaultCamelContext
import org.springframework.context.annotation.PropertySource
import org.springframework.stereotype.Component

@PropertySource("classpath:oxzion.properties")
@Component
class ElasticIndexer extends RouteBuilder {
    @Override
    void configure() throws Exception {
        CamelContext context = new DefaultCamelContext()
        context.addRoutes(new RouteBuilder() {
            @Override
            public void configure() {
                PropertiesComponent propc = getContext().getComponent("properties", PropertiesComponent.class)
                String index=""
                String id=""
                String operation = ""
                propc.setLocation("classpath:oxzion.properties")
                from("activemq:topic:elastic").process(new Processor() {
                    public void process(Exchange exchange) throws Exception {
                        def jsonSlurper = new JsonSlurper()
                        def object = jsonSlurper.parseText(exchange.getMessage().getBody() as String)
                        index = object.index as String
                        id = object.id as String
                        operation =  object.operation as String
                        exchange.getIn().setHeader("indexName", index)
                        exchange.getIn().setHeader("id", id)
                        exchange.getIn().setHeader("operation", operation)
                    }
                }).to("log:notification").to("elasticsearch-rest://{{elasticsearch.clusterName}}?hostAddresses={{elasticsearch.host}}:{{elasticsearch.port}}")

            }
        })
        context.start()
    }
}
