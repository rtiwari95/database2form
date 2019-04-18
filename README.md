

	• Generate large number of forms within minutes
	• Optional login feature with basic functionality
	
Dependencies
---
	• Twitter Bootstrap
	• jQuery
	
How to use
---

	• Copy this project to xampp\htdocs.
	
	• Import here database file << sample.sql >>.
	
	• Add database settings to db_config.php file.
	
	• Open browser and type url <hostname>/database2form.
		e.g. localhost/database2form(make sure your apache and mysql server is running).
		
	• Now fill the database details in the form e.g. RDBMS, host IP on which RDBMS is running, RDBMS username and password.
	
	• click List database button a new page will open with the list of databases present to your database.
	
	• Thereafter select the database for which you want to create form (CRUD project) followed by tables you want to use in your 		project.
	
	• After selecting tables and clicking on proceed button you will be redirected to a new page where
		1. You can enter your Projects name (Avoid use of spaces and special character except ‘$’ and ‘_’ in project name).	
		2. Fields of tables you need in your project and their corresponding type (Note that the fields with NOT NULL property 				in database are by default selected and disabled to be unselected as their value is necessary in database and the 				dropdown containing database type is only enabled when corresponding field is selected).
		3. Whether login functionality is required to your new page or not (Note that if you select login functionality two 				tables will be created to your selected database <dbname>_users and <dbname>_user_logwith default entry of admin so			    make sure that you have create user privilege to selected database).
		
	• Click Create Form to get desired forms generated.
	• After downloading the project extract it using winrar or any other decompressing tool.
	
	•  Copy and paste it(decompressed project) to your xampp\htdocs directory and in browser type url <Hostname>/<your project 			name> .
		e.g.: localhost/test (where 'test' is generated project's name).
		
	• You can also modify the generated project according to your need and choice.
