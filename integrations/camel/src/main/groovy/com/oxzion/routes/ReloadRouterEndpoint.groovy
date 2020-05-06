package com.oxzion.routes
import org.apache.camel.builder.RouteBuilder
import org.springframework.stereotype.Component

//@Component
public class ReloadRouterEndpoint extends RouteBuilder {

    @Override
    public void configure() throws Exception {

        from("jetty://http://0.0.0.0:8888/activateRouteUpdateListener")
                .log("Routes Update listener activating..")
                .setBody(simple("Routes Update listener activated"))
                .to("log:out")
                .to("http4://0.0.0.0:8085/reloading/activateListenerForRoutesUpdate?bridgeEndpoint=true")
    }
}
