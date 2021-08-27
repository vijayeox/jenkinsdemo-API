<div align="center">
  <h1>EOS-3.0 BUG FIXES FOR COMMON ERRORS</h1>
  <p>
    Document describing common bugs and their fixes in servers.
  </p>
</div>
----

<div align="center">
<h3><u>CRM</u>:</h3>
</div>
-------------------------

1. **CRM not loading due to internal server error:**
	- Open Developer tools for your respective browser i.e for **chrome** using keyboard shortcut **(ctrl + shift + i)** .
	
	- Clear console first and reopen the CRM application or right-click and select reload/reload-frame to see the **GET call** being made by the application for crm which begins with this **_ https://xyz.eoxvantage.com:9075/crm/public/index.php _**
	
	-  Right-click on the **GET call** in the console tab in Developer tools window and select open in a new tab. Goto the newly opened tab and change the index.php to index_dev.php in the url to see the errors in development mode.
	
	- If there are no errors but only warnings it might be just cache issue and cache needs to be cleared in the server manually or by triggering script kept inside **commonbugfixes** inside home folder of servers.
	- Run the script **crmcacheissue.sh** in /home/ubuntu/commonbugfixes or do the following steps
		
		
		- sudo service supervisor stop
		
		- sudo rm -rf /var/www/crm/var/cache/*
		
		- sudo chown www-data:www-data var -R && sudo chmod 777 var/ -R
		
		- sudo service supervisor restart