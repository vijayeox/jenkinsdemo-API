package org.firespike.smtp.server;

import org.subethamail.smtp.server.SMTPServer;

public class BasicSMTPServer {
	public static void main(String[] args) {
		FileMessageHandlerFactory myFactory = new FileMessageHandlerFactory() ;
		SMTPServer smtpServer = new SMTPServer(myFactory);
		int port = 25;
		if(args.length ==1){
			port = Integer.parseInt(args[0]);
		}
		smtpServer.setPort(port);
		smtpServer.start();
	}
}


