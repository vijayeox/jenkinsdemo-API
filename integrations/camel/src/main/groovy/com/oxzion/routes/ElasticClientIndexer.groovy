package com.oxzion.routes

import groovy.json.JsonOutput
import groovy.json.JsonSlurper
import org.apache.camel.CamelContext
import org.apache.camel.Exchange
import org.apache.camel.Processor
import org.apache.camel.builder.RouteBuilder
import org.apache.camel.component.properties.PropertiesComponent
import org.apache.camel.impl.DefaultCamelContext
import org.apache.http.HttpHost
import org.elasticsearch.action.bulk.BulkRequest
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
                        def idList
                        String ID;
                        String indexName = object.index.toString().toLowerCase()
                        String type = object.type.toString()
                        if(object.containsKey('id')){
                            ID = object.id.toString()
                        }
                        if(object.containsKey('idlist')){
                            idList = object.idlist
                        }
                        String operation = object.operation.toString()
                        def output = JsonOutput.toJson(object.body)
                        def client = new RestHighLevelClient(
                        RestClient.builder(new HttpHost(HOST, PORT, "http")))
                        if(operation == 'Index')
                        {
                            def request = new IndexRequest(indexName,type,ID)
                            request.source(output, XContentType.JSON)
                            client.index(request, RequestOptions.DEFAULT)
                        }
                        else if(operation == 'Delete')
                        {
                            def deleteRequest = new DeleteRequest(indexName,type,ID)
                            client.delete(deleteRequest, RequestOptions.DEFAULT)
                        }
                        else if(operation == 'Batch')
                        {
                            int i = 0
                            BulkRequest bulk = new BulkRequest()
                            for(obj in object.body) {
                                String id = idList[i].toString()
                                ++i
                                bulk.add(new IndexRequest(indexName,type,id).source(obj.value, XContentType.JSON))
                            }
                            client.bulk(bulk,RequestOptions.DEFAULT)
                        }
                        else
                        {
                            throw new Exception("Incorrect operation specified :" + operation)
                        }
                    }
                }).to("log:notification")

            }
        })
        context.start()
    }
}
