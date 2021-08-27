package org.firespike.smtp.server;

import org.subethamail.smtp.*;
import java.io.File;
import java.io.BufferedWriter;
import java.io.BufferedReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.text.SimpleDateFormat;
import java.util.Date;

public class FileMessageHandlerFactory implements MessageHandlerFactory {
	
    public MessageHandler create(MessageContext ctx) {
        return new Handler(ctx);
    }

    class Handler implements MessageHandler {
        MessageContext ctx;
	private SimpleDateFormat dtDate = new SimpleDateFormat("yyyyMMdd");
    	private SimpleDateFormat dtTime = new SimpleDateFormat("HHmmssSSS");
	private String from;
	private String recipient;

        public Handler(MessageContext ctx) {
        	this.ctx = ctx;
        }
    	
        public void from(String from) throws RejectException {
		this.from = from;
        }

        public void recipient(String recipient) throws RejectException {
		this.recipient = recipient;        	
        }

        public void data(InputStream data) throws IOException {
		BufferedWriter bw = null;
		FileWriter fw = null;
		File f = null;
        	try {
			Date dt = new Date();
			String folder = String.format("outbox/%s/%s", from, dtDate.format(dt));
			f = new File(folder);
			f.mkdirs(); 
			String message = convertStreamToString(data);

			fw = new FileWriter(folder + "/text-"+ dtTime.format(dt)+".txt");
			bw = new BufferedWriter(fw);
			bw.write(message);
			bw.close();
		    
		} finally {
			if(fw != null){
				fw.close();
			}
			if(bw != null){
				bw.close();
			}
		}
        }

        public void done() {
        	//System.out.println("Finished");
        }

    	public String convertStreamToString(InputStream is) {
    		BufferedReader reader = new BufferedReader(new InputStreamReader(is));
    		StringBuilder sb = new StringBuilder();
    		
    		String line = null;
    		try {
    			while ((line = reader.readLine()) != null) {
    				sb.append(line + "\n");
    			}
    		} catch (IOException e) {
    			e.printStackTrace();
    		}
    		return sb.toString();
    	}

    }
}
