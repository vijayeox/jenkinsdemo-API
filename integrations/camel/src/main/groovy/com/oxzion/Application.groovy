package com.oxzion

import java.util.Collections

import org.springframework.boot.SpringApplication
import org.springframework.boot.autoconfigure.SpringBootApplication
import org.springframework.boot.autoconfigure.EnableAutoConfiguration

//CHECKSTYLE:OFF
@SpringBootApplication
@EnableAutoConfiguration

class Application{
    static void main(String[] args){
        if(System.getenv('SERVER_PORT')){
            SpringApplication app = new SpringApplication(Application.class)
            app.setDefaultProperties(Collections
                    .singletonMap("server.port", System.getenv('SERVER_PORT')))
            app.run(args)
        } else {
            SpringApplication.run Application, args
        }
    }
}