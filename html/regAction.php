<?php
//regAction.php

require('/var/www/secrets/conf.php');
$datetime = date("U");

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

date_default_timezone_set('America/Los_Angeles');   //TODO:  Customize variable

$session = bin2hex(random_bytes(16));   //initialized
$session = 'myInitializerErrorNoSession'.$session;

if (isset($_SESSION['sessionID'])){
$session = $_SESSION['sessionID'];
//$session = session_id();
}

 $obj = new stdClass();
     $obj->datetime = $datetime;
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

$regdate=$datetime;

if(!($displayname=@$_POST["displayname"])){$obj->error = 'No displayname included.';echo json_encode($obj);exit;}
else {$displayname=$_POST["displayname"];}
$obj->displayname = $displayname;

if(!($email=@$_POST["email"])){$obj->error = 'No email included.';echo json_encode($obj);exit;}
else {$email=$_POST["email"];}
$obj->email = $email;

if(!($password=@$_POST["password"])){$obj->error = 'No password included.';echo json_encode($obj);exit;}
else {$password=$_POST["password"];}

require('/var/www/html/classlib/pwhash.php');
$pwhash=pwhash::get64char($password);

require('/var/www/html/classlib/Code.php');
$code = Code::get16chars();

//check if email exists and 
$available=false;
$doemail=false;

$query = "SELECT email
          FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($emaildb);
if (mysqli_connect_errno()) {$obj->error = 'Error: Could not SELECT';
                            echo json_encode($obj);
                            exit;}
else{
    if($stmt->num_rows == 1) {
        $obj->error = 'none';
         while($stmt->fetch()){
            $email = $emaildb;
         }
    } elseif($stmt->num_rows == 0) {
         $available = true;       
    } else {$obj->error = 'Database Error';}
}

$verified='no';
$insertcode=FALSE;

if ($available){
    $query = "INSERT INTO users VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssssss', $email, $verified, $pwhash, $regdate, $displayname, $obj->dateread);
    $stmt->execute();
    if($db ->affected_rows == 1){
                            $obj->message = $obj->message."Users: 1 row affected.  ";
		     	    $insertcode=true;
                        }else{
		       	    $insertcode=FALSE;
                            $obj->message = $obj->message."Failed to create new user.  ";
                        }
}else{
    $obj->error = 'regAction: Account with this email already exists.';
    error_log(json_encode($obj));
}

$initialize='yes';
if($insertcode){
    $query = "INSERT INTO codes VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('sss', $email, $code, $initialize);
    $stmt->execute();
    if($db ->affected_rows == 1){
                            $obj->message = $obj->message."Codes: 1 row affected.  ";
                            $doemail=true;
                        }else{
                            $obj->message = $obj->message."Failed to store code.  ";
                            $doemail=false;
                        }
}

if($doemail){

//webRoot is configured in your conf.php file
$link = $webRoot.'/verify.php?code='.$code;    

$recipient = $email;
    
// The subject line of the email
$subject = 'Please, confirm your email address.';

// The plain-text body of the email
$bodyText =  'To sign in, please follow this link (or paste into address bar): '.$link. '  This link is one-time-use only.  No need to keep this email.';

// The HTML-formatted body of the email
$bodyHtml = '<h1>Confirm Email</h1>
    <p>To sign in, please follow this link (or paste into address bar): <a href="'.$link. '">'.$link. '</a>  This link is one-time-use only.  No need to keep this email.</p>';

    
include('/usr/local/lib/php/Mail.php');
$recipients = $recipient;
$headers['From']= $noreply;
$headers['To']= $recipient;
$headers['Subject'] = $subject;
$body = $bodyText;
$params['host'] = $mailserverhost;
$params['port'] = $mailserverport;
$params['auth'] = $mailauth;
$params['username'] = $usernameSmtp;
$params['password'] = $passwordSmtp;

//$params['debug'] = 'true';
$mail_object =& Mail::factory('smtp', $params);

//foreach ($params as $p){
// echo "$p<br />";
//}

// Send the message
$mail = $mail_object->send($recipients, $headers, $body);

if (PEAR::isError($mail)) {
    $obj->error = $mail->getMessage();
    error_log(json_encode($obj));
} else {
    $obj->message=$obj->message.' Email sent successfully. ';
}
    
echo json_encode($obj);
exit;
    
}//end if(doemail)
else{
    $obj->error='regAction: doemail is false';
    $obj->message=$obj->message.' No email sent.';
    echo json_encode($obj);
    error_log(json_encode($obj));
    exit;
}

?>

