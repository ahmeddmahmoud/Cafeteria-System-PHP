# Cafeteria-System-PHP

To configure the PHP mail() function to use SMTP with Gmail, you need to modify the php.ini configuration file.
Here's how you can set it to use SMTP server smtp.gmail.com and port 587:

Open your php.ini file in a text editor. 
This file is usually located in your PHP installation directory.
Find the section for [mail function].
Update the SMTP parameter to smtp.gmail.com and the smtp_port parameter to 587.
Save the changes to the php.ini file.
Restart your web server for the changes to take effect.
