Elemovements News - The Ultimate News Management System

// VERSION 2.0.2 
// released: 19th of February, 22:30 GMT+2 time

 ======================================================
|	Copyright 2010 - Andrei Dumitrache
|	www.dumitrache.net
 ======================================================

 _________________
/==Contents=======\___
|
| 1. Disclaimer  
| 2. License
| 3. What's new?
| 4. Requirements
| 5. Installing
| 6. Showing the news
| 7. Contact
|_______________________
 ______________
| Disclaimer:  \_________________________________________________________

 THIS PROGRAM IS PROVIDED TO YOU FOR FREE AND THEREFORE
 WITHOUT ANY WARRANTY. If you experience any problems using it, I
 will be more than happy to help you... HOWEVER, I am not obligated 
 to do so. For more details, see the attached license file: license.txt

 __________
| License  \_____________________________________________________________


Please read the file "license.txt"
Please note that you are not allowed to remove the "Powered By..." hyperlink without registering. (*)

*To find out how to register visit http://www.xpression-news.com/professional.php

 __________________________________________
| What's new?  New features, fixed bugs... \_________________________________


Fixed bugs from Elemovements News 2.0.0

- Writing in news and comments file is now safe against concurent I/O operations
- Fixed security bug

New Features since Elemovements News 1:

- Synchronizer now works for CuteNews
- Easy integration for AdSense & other Ad Serving programs
- Added a WYSIWYG Editor
- Added "Category Icon"
- Postponed articles
- Friendly installer - allowing you to install the script in seconds
- Multilanguage support (2 more languages)
- Integration Wizard (automatically integrate X-News into your website) 
- Users can now disable/enable the comments board
- Registered users can now log in from the comments board
- Registered users can prevent their names from being used by other people on the comments board
- Publishers can now add pictures automatically from the Image Uploader. There's no need to specify the correct url to the newly uploaded picture
- Backup engine - this feature allows you to create/restore backups of your news,comments, user database, news category database and rank database
- Non-English characters are now supported in the title of the news article

Fixed bugs:

-  news.php and archives.php vulnerability fixed.
-  Fixed a great number of errors in the html design of the skin and template files

 __________________________
| Requirements             \__________________________
|
|    [+] A website ( :) )
|    [+] FTP access to your site
|    [+] PHP installed
|    [+] GD library installed*


That's about it! You don't need more than that! All the information is stored in files. No need for MySql access.

* X-News will work without it but the "Save Icon on Server" option must be deactivated to prevent some nasty errors from occurring. Don't worry; the GD library is generally loaded by default. If not, you need to ask your web host to load it.

 ____________________________
| Installing Elemovements News  \_______________________

   1. Open your favourite ftp client and create a new folder on your server
   2. Change Permissions on the created folder: 777*
   3. Upload all the contents of the ZIP archive to that folder
   4. Select all the files uploaded and Change Permissions to 777. 
       --IF YOU DO NOT SET APPROPIATE PERMISSIONS, YOU WILL GET A CHAIN OF ERRORS “failed to open stream” -- 
   5. Open your web browser and access the url where you uploaded
       the script. An automatic installer will start
   6. Follow the instructions and don't forget to delete the file 'install.php' from 
       your server after you install the script. X-News will not function with it present.
   
*CHMOD-ing to 777:

Some servers have a special PHP module installed that doesn’t allow files to be CHMOD-ed to 777. If you try to set a 777 mode on a file, you will get “Internal Server Error”. Fix: try setting permissions to 755 instead of 777.
 _______________________________
| Showing the news on your site \______________________

  OK. You installed the script successfully. Now you need to get the news you post 
  to show up on your site. X-News outputs news on two files: news.php (active news)
  and (archives.php - all the news ever posted). To get the news on a particular page
  of your website, you must include one of these two files. You can do that by entering
  the following codes in the page where you want the news displayed (page extension must be .php).

 <?php include("xnews/news.php");?> For showing active news
 <?php include("xnews/archives.php");?> For showing all the news
 
 *replace "xnews" with the path to the directory where you just installed X-News

That's it! You're done :))
 
EXTRA: Each of these two files support some inclusion parameters - variables that are inserted before the include() line

  $catlist="1,5,6";                      // The script will only display news recorded in these categories. Use Id instead of name.
  $authorlist="Author1,Author2";    // The script will only display news posted by these authors;   

 _____________________
| Contact             \___________________________________

Hate mails, complains and questions will be directed to this address:
 
contact@xpression-news.com

Please read the license before contacting me
