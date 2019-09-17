package com.oxzion.routes

import groovy.json.JsonSlurper
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.component.properties.PropertiesComponent
import org.apache.camel.impl.DefaultCamelContext
import org.apache.http.HttpHost
import org.elasticsearch.action.delete.DeleteRequest
import org.elasticsearch.action.index.*
import org.elasticsearch.client.RequestOptions
import org.elasticsearch.client.RestClient
import org.elasticsearch.client.RestHighLevelClient
import org.elasticsearch.common.xcontent.XContentType
import org.springframework.context.annotation.PropertySource
import org.springframework.stereotype.Component
import org.springframework.core.env.Environment
import org.springframework.beans.factory.annotation.Autowired

@PropertySource("classpath:oxzion.properties")
@Component
class ElasticClientIndexer extends RouteBuilder {

    @Autowired
    private Environment env

    @Override
    void configure() throws Exception {
        CamelContext context = new DefaultCamelContext()
        context.addRoutes(new RouteBuilder() {
            @Override
            public void configure() {
                PropertiesComponent propc = getContext().getComponent("properties", PropertiesComponent.class)
                propc.setLocation("classpath:oxzion.properties")
                from("activemq:topic:elastic").process(new Processor() {
                    public void process(Exchange exchange) throws Exception {
                        def jsonSlurper = new JsonSlurper()
                        def object = jsonSlurper.parseText(exchange.getMessage().getBody())
                        def HOST = env.getProperty("elastic.host")
                        int PORT = env.getProperty("elastic.port").toInteger()

                        String indexName = object.index.toString().toLowerCase()
                        String type = object.type.toString()
                        String id = object.id.toString()
                        String operation = object.operation.toString()
                        def client = new RestHighLevelClient(
                                RestClient.builder(new HttpHost(HOST, PORT, "http")))
                        if(operation == 'Index')
                        {
                            def request = new IndexRequest(indexName,type,id)
                            request.source(exchange.getMessage().getBody(), XContentType.JSON)
                            client.index(request, RequestOptions.DEFAULT)
                        }
                        else if(operation == 'Delete')
                        {
                            def deleteRequest = new DeleteRequest(indexName,type,id)
                            client.delete(deleteRequest, RequestOptions.DEFAULT)
                        }
                        else
                        {
                            throw new Exception("Incorrect operation specified :"+operation)
                        }
                    }
                }).to("log:notification")

            }
        })
        context.start()
    }
}
