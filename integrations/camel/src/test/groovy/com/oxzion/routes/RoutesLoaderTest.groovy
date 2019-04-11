package com.oxzion.routes

import org.junit.Test
import static org.junit.Assert.*
import static org.junit.Assume.*

public class RoutesLoaderTest {
    @Test
    public void testRoutes() {
        //System.setProperty("ROUTE_CONFIG", System.getProperty('user.dir')+"/src/test/config");
        println "property - ${System.getProperty('ROUTE_CONFIG')}"
        RoutesLoader loader = new RoutesLoader()
        def config = loader.loadRoutes()
        println(config.routes.route)
        assertNotNull(config)    
    }
}