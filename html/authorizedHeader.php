<?php
//authorizedHeader.php  header for pages inside dashboard and utilities
session_start();
if(!isset($_SESSION['sessionID'])) 
    { 
        header('Location: https://'.$_SERVER['SERVER_NAME'].'/signin.php'); exit;
    }
require('/var/www/secrets/conf.php');
 $datetime = date("U");
 date_default_timezone_set('America/Los_Angeles');                              //TODO:  Customize variable
 $docroot = @$_SERVER['DOCUMENT_ROOT'];

$session = bin2hex(random_bytes(64));   //initialized 
$session = 'myInitializerErrorNoSessionSet'.$session;   //initialized 
if (isset($_SESSION['sessionID'])){
$session = $_SESSION['sessionID'];
}

 $obj = new stdClass();
     $obj->dateread = date("D M j G:i:s T Y");
     $obj->message = '';
     $obj->error = 'none';

 $db = new mysqli($dbserver, $dbuser, $userpw, $database);
 if (mysqli_connect_errno()) {
                             $obj->error = 'Error: Could not connect to database.';
                             echo json_encode($obj);
                             exit;
                             }else{
                             $obj->message = $obj->message.'Successfully connected to database.  ';
                             }

 //check session
 $displayname = 'initializer';
 $email = bin2hex(random_bytes(64));   //initialized
 $authorized=FALSE;
 $checksession = bin2hex(random_bytes(64));   //initialized
 $query = "SELECT sessions.email, sessions.sessionid, users.displayname
           FROM sessions LEFT JOIN users
           USING (email)
           WHERE sessions.sessionid = ?";
 $stmt = $db->prepare($query);
 $stmt->bind_param('s', $session);
 $stmt->execute();
 $stmt->store_result();
 $stmt->bind_result($emaildb, $sessiondb, $displaydb);
 if (mysqli_connect_errno()) {$obj->error = 'Error: Could not connect to database.  ';
                             echo json_encode($obj);
                             exit;
 }
 else{
     if($stmt->num_rows == 1) {
          while($stmt->fetch()){
             $email = $emaildb;
             $checksession = $sessiondb;
             $displayname = $displaydb;
          }
                  if(strcmp($session, $checksession)==0){
                               $authorized=TRUE;
                               $obj->email = $email;
                               $obj->message=$obj->message.'Authorized!  ';
                               $obj->displayname = $displayname;
                   }
     } elseif($stmt->num_rows == 0) {
          $obj->message=$obj->message.'Session not found, no match.  ';
     } else {
          $obj->error = 'Database Error: Sessions not 1 or 0.  ';
         echo json_encode($obj);
         exit;
    }
}

if(!$authorized){header('Location: https://'.$_SERVER['SERVER_NAME'].'/signin.php'); exit;}

?>


<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title><?php echo $sitename; ?></title>
<link rel="stylesheet" type="text/css" href="/css.css">
<style>
<?php
if($authorized){
    echo '.guest { display: none; }';
}else{
    echo '.authorized { display: none; }';
}
?>
</style>
</head>
<body id="grad1">
<!-- _______________________PAGE HEADER____________________________-->
<header>

<div class="topHead">
    <div class="topleft" ><a class="buttonHome" href="<?php echo $webRoot; ?>"><?php echo $sitename; ?></a></div>
    <div class="topright"><a class="buttonSignIn authorized" href="/dashboard/session.php"><?php echo $email;?></a></div>
    <div class="topright"><a class="buttonSignIn authorized" href="/logout.php">Log Out</a></div>
    <div class="topright"><a class="buttonSignIn guest" href="/signin.php">Sign In</a></div>
    <div class="topright"><a class="buttonSignIn guest" href="/register.php">Register</a></div>
</div>
<div style="clear:both"></div>
</header>



