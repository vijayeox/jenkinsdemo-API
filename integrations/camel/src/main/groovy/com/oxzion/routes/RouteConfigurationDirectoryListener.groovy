package com.oxzion.routes
import org.apache.commons.io.monitor.FileAlterationListener
import org.apache.commons.io.monitor.FileAlterationListenerAdaptor
import org.apache.commons.io.monitor.FileAlterationMonitor
import org.apache.commons.io.monitor.FileAlterationObserver
import org.slf4j.Logger
import org.slf4j.LoggerFactory
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.stereotype.Component

//@Component
public class RouteConfigurationDirectoryListener {


    private static final Logger logger = LoggerFactory.getLogger(RouteConfigurationDirectoryListener.class)

    //The directory to listen for changes : contains the Routes.groovy
    private static final String DIRECTORY_NAME = "./../../../../../../../camel/"
    private static final long POLL_INTERVAL = 3000

   // @Autowired
    RoutesLoader loader

    public  void listenFile() throws  Exception{
        FileAlterationObserver observer = new FileAlterationObserver(DIRECTORY_NAME)
        FileAlterationMonitor monitor = new FileAlterationMonitor(POLL_INTERVAL)
        FileAlterationListener listener = new FileAlterationListenerAdaptor() {
            @Override
            public void onFileCreate(File file) {
                // code for processing creation event
                println("File has been added : "+file.getName())
            }

            @Override
            public void onFileDelete(File file) {
                // code for processing deletion event
                println("File will been deleted : "+file.getName())
            }

            @Override
            public void onFileChange(File file) {
                // code for processing change event
                println("File has been Moddified : "+file.getName())
                try {
                    //System.out.println(loader.loadRoutes())
                    loader.configure()
                } catch (Exception e) {
                    logger.error(e.getMessage())
                }
               logger.info("Routes reloaded")
            }
        }
        observer.addListener(listener)
        monitor.addObserver(observer)
        monitor.start()
    }
}
