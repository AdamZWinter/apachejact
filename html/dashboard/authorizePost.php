<?php
// dashboard / authorizePost.php

session_start();
date_default_timezone_set('America/Los_Angeles');
$datetime = date("U");
$obj = new stdClass();
     $obj->datetime = $datetime;
     $obj->dateread = date("D M j G:i:s T Y");
     $obj->message = '';

if(!isset($_SESSION['sessionID']))
    { 
        $obj->error = 'No session is set.  Please sign in again.';
        echo json_encode($obj);
        exit;
    }

require('/var/www/secrets/conf.php');

$email = bin2hex(random_bytes(64));   //initialized
$session = bin2hex(random_bytes(64));   //initialized

if(isset($_SESSION['sessionID'])){
$session = $_SESSION['sessionID'];
}

 $db = new mysqli($dbserver, $dbuser, $userpw, $database);
 if (mysqli_connect_errno()) {
                             $obj->error = 'Error: Could not open database.';
                             echo json_encode($obj);
                             exit;
                             }else{
                             $obj->message = $obj->message.'Successfully opened database.  ';
                             }

 //check session
 $authorized=FALSE;
 $checksession = bin2hex(random_bytes(64));   //initialized
 $query = "SELECT email, sessionid
           FROM sessions WHERE sessionid = ?";
 $stmt = $db->prepare($query);
 $stmt->bind_param('s', $session);
 $stmt->execute();
 $stmt->store_result();
 $stmt->bind_result($emaildb, $sessiondb);
 if (mysqli_connect_errno()) {$obj->error = 'Error: Could not connect to database.  ';
                             echo json_encode($obj);
                             exit;
 }
 else{
     if($stmt->num_rows == 1) {
          while($stmt->fetch()){
             $email = $emaildb;
             $checksession = $sessiondb;
          }
                  if(strcmp($session, $checksession)==0){
                               $authorized=TRUE;
                               $obj->message=$obj->message.'Authorized!  ';
                               $obj->email = $email;
                   }
     } elseif($stmt->num_rows == 0) {
          $obj->error='Session not found.  Please log in again.  ';
          echo json_encode($obj);
          exit;
     } else {
          $obj->error = 'Database Error: Sessions not 1 or 0.  Please log out and log in again.  If problem persists, please contact administration.';
         echo json_encode($obj);
         exit;
    }
}

if(!$authorized){header('Location: '.$webRoot.'/signin.php'); exit;}
?>
