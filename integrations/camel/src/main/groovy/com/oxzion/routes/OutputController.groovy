package com.oxzion.routes
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.GetMapping
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RestController

//@RestController
//@RequestMapping("reloading")
class OutputController {
    private static final Logger logger = LoggerFactory.getLogger(OutputController.class)

    //@Autowired
    RouteConfigurationDirectoryListener listener

    //@GetMapping("/activateListenerForRoutesUpdate")
    public  String reload() throws Exception {
        listener.listenFile()
        logger.info("Routes can be updated")
        return "activateListenerForRoutesUpdated: Now Routes can be updated and automatically reloaded"
        //Script script = new GroovyShell().parse(new File("src/main/groovy/com/oxzion/routes", "RoutesLoader.groovy"))
        //return (String) script.run()
        //return (String) new GroovyShell().parse( new File( "src/main/groovy/com/oxzion/routes/RoutesLoader.groovy" ) ).invokeMethod( "loadRoutes", "" )
    }

}