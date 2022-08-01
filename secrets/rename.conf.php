<?php 
//Domain:
//Put here whatever will resolve to your webroot (ex.  https://example.com OR http://www.example.com)
//do not include the trailing slash
//Some parts of this site, like email verification, will link to https://example.com
$webRoot = 'https://dmarc.topsecondhost.com';

//For Databasei
$dbserver='mariadb'; 
$dbuser='adam'; 
$userpw='changethisJGGHKgkhAawl'; 
$database='universaldb';

 
//Custom Error Logging file 
//This is not used anywhere by default 
//By default only the standard PHP error log file is used 
$logFile='/var/www/logs/phperrors.log'; 

 
//Toggle debugging on and off here only
//Toggling this on (TRUE) will display Obj on the pages to users.
//Be careful using this, and make sure Obj does not have sensitive info in it 
$debugging = FALSE; 

//Sitename: for display purposes.
//This is used in Title and upper left home button of the header 
$sitename='DMARC Now'; 

//This is a generic multi-purpose hash of a password of your choosing
//Use this as a verification for miscellaneous functions like utilities
//To use utilities (editor.php & uploadEditor.php) you must enter the password on that page that hashes to this value
//To configure this value, copy the pwhash value from the users table of the database
//OR see /var/www/html/users/pwhash.php for the hash used for all passwords on the site  
$pw='g0MGU1MGNkYjViZD2E4ZWJlODQ5OGQzOTc3NmFhZT';

//__________FOR SENDING EMAILS WITH SES_______________________________________

//For PHP Mailer Class 
$usernameSmtp = 'noreply'; 
$passwordSmtp = 'GXJGGHKgkZFlW'; 


// Replace sender@example.com with your NoReply "From" address.
$noreply = 'noreply@topsecondhost.com';
$noreplyName = 'No Reply';

// If you're using Amazon SES in a region other than US West (Oregon),
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
// endpoint in the appropriate region.
$mailserverhost = '10.0.0.6';
$mailserverport = 25;
$mailauth = false;


// (OPTIONAL) Specify a configuration set. If you do not want to use a configuration
// set, comment or remove the next line.
//$configurationSet = 'ConfigSet';



 //_____________For Oauth APIs ______________________________________________ 

 //Google API 
$client_id='clietid'; 
$client_secret='apikey'; 
$redirect_uri='https://topsecondhost.com/example/redirect.php'; 


 //Paypal: 
//Comment out the id and secret that you are not using (sandbox or live). 

 //$ppEndpoint = 'https://api.sandbox.paypal.com'; 
$ppEndpoint = 'https://api.paypal.com'; 

 //Paypal Sandbox only 
//$paypalid='longstringofcharacters'; 
//$paypalsecret='longstringofcharacters'; 

 //Paypal Live 
$paypalid='longstringofcharacters'; 
$paypalsecret='longstringofcharacters'; 

 ?>
