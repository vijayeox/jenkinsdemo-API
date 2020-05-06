package com.oxzion.routes

import org.apache.commons.io.FileUtils
import org.junit.Test
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.test.context.junit4.SpringJUnit4ClassRunner

import static org.junit.Assert.*

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


    @Test
    public void testRoutesUpdated(){
        RouteConfigurationDirectoryListener listener = new RouteConfigurationDirectoryListener()
        assertNotNull(listener)
        RoutesLoader loader = new RoutesLoader()
        //load the test file
        def config = loader.loadRoutes("test")
        int oldRouteCount = config.routes.route.size();
        println("Old Routes count: "+ oldRouteCount) // existing routes
        listener.listenFile(); //activate listener
        //update test router file by inserting new route at the beginning
        File f1 = new File("./../camel/testReload/camelRoutesTest.groovy")
        FileReader fileReader = new FileReader(f1)
        BufferedReader br = new BufferedReader(fileReader)
        String fileContent = FileUtils.readFileToString(f1)
       fileContent = fileContent.replace("route = [",
                "route = [" +
                        "['from':'activemq:topic:FILE_DELETED_TEST','to':[\"\${callback.URL}/fileindexer\"]],")
        FileUtils.write(f1,fileContent)
        println("test file updated")

        config = loader.loadRoutes("test")
        int newRouteCount = config.routes.route.size();
        println("new routes count: "+ newRouteCount)
        //Test new route added
        assertEquals(oldRouteCount+1,newRouteCount)
    }
}