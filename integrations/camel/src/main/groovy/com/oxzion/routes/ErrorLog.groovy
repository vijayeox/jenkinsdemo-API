package com.oxzion.routes

import groovy.sql.Sql

import java.text.SimpleDateFormat

class ErrorLog {

    static log(String error_type, String  error_trace, String payload, String params) {
       try {
            InputStream input = ErrorLog.class.getClassLoader().getResourceAsStream("oxzion.properties")
            Properties prop = new Properties()
            if (input == null) {
                System.out.println("Sorry, unable to find oxzion.properties")
                return
            }
            prop.load(input)
            Date now = new Date()
            String pattern = "yyyy-MM-dd HH:mm:ss"
            SimpleDateFormat formatter = new SimpleDateFormat(pattern)
            String mysqlDateString = formatter.format(now)
            def sql = Sql.newInstance(prop.getProperty("db.host"), prop.getProperty("db.user"), prop.getProperty("db.password"), "com.mysql.jdbc.Driver")
            sql.execute("INSERT INTO ox_error_log (error_type, error_trace,payload,date_created,params) values (${error_type}, ${error_trace},${payload},${mysqlDateString}, ${params})")
            sql.close()
            System.out.println("handling ex")
            prop.load(input)
        }  catch (IOException ex) {
            ex.printStackTrace()
        }
    }
}
