<?php
//indexHeader.php
session_start();
if(!isset($_SESSION['sessionID'])) 
    { 
	session_destroy();
        ini_set('session.cookie_lifetime', 0);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1);
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.use_trans_sid', 0);
        ini_set('session.hash_function', 'sha512');
        ini_set('session.sid_length', '64');
        session_start();

        $forhash=random_bytes(128);
        $hash = openssl_digest($forhash, "sha256");
        $sid = base64_encode($hash);
        $sid = str_replace('+', '', $sid);
        $sid = str_replace('/', '', $sid);
        $sid = substr($sid, 10, 64);
        $_SESSION['sessionID']=$sid;
    }

require('/var/www/secrets/conf.php');
 $datetime = date("U");
 date_default_timezone_set('America/Los_Angeles');   //TODO:  Customize variable
 $docroot = @$_SERVER['DOCUMENT_ROOT'];

$session = bin2hex(random_bytes(16));   //initialized
$session = 'myInitializerErrorNoSession'.$session;

if (isset($_SESSION['sessionID'])){
$session = $_SESSION['sessionID'];
}

 $obj = new stdClass();
     $obj->dateread = date("D M j G:i:s T Y");
     $obj->message = 'Msg: ';
     $obj->error = 'none';
     $obj->displayname = 'initialized';

 $db = new mysqli($dbserver, $dbuser, $userpw, $database);
 if (mysqli_connect_errno()) {
                             $obj->error = 'Error: Could not connect to database.';
                             echo json_encode($obj);
                             exit;
                             }else{
                             $obj->message = $obj->message.'Successfully connected to database.  ';
                             }

 //check session
 $email = bin2hex(random_bytes(64));   //initialized
 $authorized=FALSE;
 $checksession = bin2hex(random_bytes(64));   //initialized
 $displayname = 'Error found initilaizer value';
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
                               $authorized=true;
                               $obj->message=$obj->message.'Authorized!  ';
                                $obj->displayname = $displayname;
                   }
     } elseif($stmt->num_rows == 0) {
          $obj->message=$obj->message.'Session not found.  ';
     } else {
          $obj->error = 'Database Error: Sessions not 1 or 0.  ';
         echo json_encode($obj);
         exit;
    }
}

?>


<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<!-- Edit the conf file to change the site name -->
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
    <div class="topleft" ><a class="buttonHome" href="index.php"><?php echo $sitename; ?></a></div>
    <div class="topright"><a class="buttonSignIn authorized" href="/dashboard/session.php"><?php echo $email;?></a></div>
    <div class="topright"><a class="buttonSignIn authorized" href="logout.php">Log Out</a></div>
    <div class="topright"><a class="buttonSignIn guest" href="signin.php">Sign In</a></div>
    <div class="topright"><a class="buttonSignIn guest" href="register.php">Register</a></div>
</div>
<div style="clear:both"></div>
</header>

<div class="row">
<div class="sectionleft col-1 row_height">
<p></p>
</div>


<div class="col-10 row_height content">
<!-- Row 1 -->
<div class="row">
  <div class="col-1 mainGridBox bigScreen">
  </div>
  <div class="col-10 mainGridBox bigScreen videoContainer">

<video width="100%" height="100%" autoplay muted>
  <source id="introVideo" src="https://topsecondhost.s3-us-west-2.amazonaws.com/InstallVideo.mp4" type="video/mp4">
</video>


  </div>
  <div class="col-1 mainGridBox bigScreen">
  </div>
</div>
<!-- Row 2 -->
<div class="row">
  <div class="col-1 mainGridBox bigScreen">
  </div>
  <div class="col-10 mainGridBox">
