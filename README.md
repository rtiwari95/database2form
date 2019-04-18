Database2Form
===
Introduction
---
A project which generates CRUD form with basic user control and security features using database structure

	• Generate large number of forms within minutes
	• Optional login feature with basic functionality
	
Dependencies
---
• [Twitter Bootstrap](https://github.com/twbs/bootstrap/releases/download/v4.3.1/bootstrap-4.3.1-dist.zip)   
• [jQuery](https://code.jquery.com/jquery-3.4.0.slim.js)
	
How to use
---

• Copy this project to xampp\htdocs.
	
• Import here database file << sample.sql >>.
	
• Add database settings to db_config.php file.
	
• Open browser and type url <hostname>/database2form.  
	`e.g. localhost/database2form(make sure your apache and mysql server is running).`
	
• Now fill the database details in the form e.g. RDBMS, host IP on which RDBMS is running, RDBMS username and password.

![f1](https://user-images.githubusercontent.com/49361836/56358389-1a281c80-61fc-11e9-9093-d057178c2703.jpg)

• Click List database button a new page will open with the list of databases present to your database.

![f2](https://user-images.githubusercontent.com/49361836/56358417-2a3ffc00-61fc-11e9-99ba-020733c24a13.jpg)
	
• Thereafter select the database for which you want to create form (CRUD project) followed by tables you want to use in your project.

![f3](https://user-images.githubusercontent.com/49361836/56358429-3330cd80-61fc-11e9-920f-d465a10c6600.jpg)
	
• After selecting tables and clicking on proceed button you will be redirected to a new page where  
	1. You can enter your Projects name (Avoid use of spaces and special character except ‘$’ and ‘_’ in project name).  
	2. Fields of tables you need in your project and their corresponding type (Note that the fields with NOT NULL property 		in database are by default selected and disabled to be unselected as their value is necessary in database and 			the dropdown containing database type is only enabled when corresponding field is selected).  
	3. Whether login functionality is required to your new page or not (**Note that** if you select login functionality two 	tables will be created to your selected database <dbname>_users and <dbname>_user_log with default entry of 			admin so make sure that you have create user privilege to selected database)
	
![f4](https://user-images.githubusercontent.com/49361836/56358447-3cba3580-61fc-11e9-9307-045890684776.jpg)
		
• Click Create Form to get desired forms generated.   

![f5](https://user-images.githubusercontent.com/49361836/56358463-46439d80-61fc-11e9-93d6-b24b96b508c3.jpg)

• After downloading the project extract it using winrar or any other decompressing tool.
	
•  Copy and paste it(decompressed project) to your xampp\htdocs directory and in browser type url `<Hostname>/<your project name>`.  
	`e.g.: localhost/test (where 'test' is generated project's name).`
		
• You can also modify the generated project according to your need and choice.

**Please leave your comments and suggestion at rtocjp@gmail.com.**
