package com.oxzion

import java.util.Collections

import org.springframework.boot.SpringApplication
import org.springframework.boot.autoconfigure.SpringBootApplication
//CHECKSTYLE:OFF
@SpringBootApplication

class Application{
    static void main(String[] args){
        print(System.getenv())
        SpringApplication app = new SpringApplication(Application.class)
        app.setDefaultProperties(Collections
                .singletonMap("server.port", System.getenv('SERVER_PORT')))
        app.run(args)
    }
}