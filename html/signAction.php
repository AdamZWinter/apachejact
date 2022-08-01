<?php
//signAction.php
//signin.php points at this page

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

//@ operator suppresses error messages so they are not shown
if(!($email=@$_POST["email"])){$obj->error = 'No email included.';echo json_encode($obj);exit;}
else {$email=$_POST["email"];}

if(!($password=@$_POST["password"])){$obj->message = 'No password included. ';}
else {$password=$_POST["password"];}

require('/var/www/html/classlib/pwhash.php');
$pwhash=pwhash::get64char($password);

//check email and password
$pwhashcheck = bin2hex(random_bytes(64));   //initialized
$pwmatches=FALSE;
$exists=FALSE;
$doemail=FALSE;
$verified=FALSE;

$query = "SELECT email, verified, pwhash
          FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($emaildb, $verifieddb, $pwhashdb);
if (mysqli_connect_errno()) {$obj->error = 'Error: Could not SELECT';
                            echo json_encode($obj);
                            exit;}
else{
    if($stmt->num_rows == 1) {
        $exists=true;
         while($stmt->fetch()){
            $email = $emaildb;
            $pwhashcheck = $pwhashdb;
            $strv = $verifieddb;
            if(strcmp($strv, 'yes')==0){$verified=TRUE;}
         }
    } elseif($stmt->num_rows == 0) {
         $exists = FALSE;
         $obj->error = 'unspecified';
         $obj->message = $obj->message.'Something is wrong with these credentials.  If the account exists, an email has been sent with a one-time login link.  ';
         echo json_encode($obj);
         exit;
    } else {$obj->error = 'Database Error';}
}

if(strcmp($pwhash, $pwhashcheck)==0){
$pwmatches=TRUE;
$obj->pwmatches="true";
}

$initialize='no';
$code = bin2hex(random_bytes(16));   //initialized
$doemail=FALSE;

require('/var/www/html/classlib/Code.php');

if (($exists && !$pwmatches) || ($exists && !$verified)){
    $code = Code::get16chars();
    $obj->code = $code;

    $query = "INSERT INTO codes VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('sss', $email, $code, $initialize);
    $stmt->execute();
    if($db ->affected_rows == 1){
                                $obj->message = $obj->message."Codes: 1 row affected.  ";
                                $doemail=true;
                            }else{
                                $obj->error = 'Error: Could not INSERT code. ';
                                $obj->message = $obj->message."Failed to store code.  ";
                                echo json_encode($obj);
                                exit;
                            }

 $query = 'DELETE FROM sessions WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if($db->affected_rows != 0){
                                $obj->message = $obj->message.'Session deleted.  ';
                            }else{
                                $obj->message = $obj->message.'No sessions deleted.  ';
                            }
$_SESSION = array();
session_destroy();
}//end if exists and not pwmatches


if($doemail){

//webRoot is configured in your conf.php file
$link = $webRoot.'/codesignin.php?code='.$code;    

$recipient = $email;
    
// The subject line of the email
$subject = 'Your link to log in.';

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
    echo json_encode($obj);
    exit;
} else {
         $obj->error = 'unspecified';
         $obj->message = $obj->message.'Something is wrong with these credentials.  If the account exists, an email has been sent with a one-time login link.  ';
         echo json_encode($obj);
         exit;
}
}//end if(doemail)
else{
 //continue to next case
}

if ($pwmatches && $verified){ 
    
    $query = 'DELETE FROM sessions WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if($db->affected_rows != 0){
                            $obj->message = $obj->message.'Session deleted.  ';
                            }else{
                            $obj->message = $obj->message.'No sessions deleted.  ';
                            }
    
    $query = 'INSERT INTO sessions VALUES (?, ?, ?)';
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssi', $email, $session, $datetime);
    $stmt->execute();
    if($db ->affected_rows == 1){
                            $obj->message = $obj->message."Sessions: 1 row affected.  ";
                            $authorized=true;
                            $_SESSION['sessionID']=$session;
                            }else{
                                $obj->error = 'Error: Could not store session. ';
                                $obj->message = $obj->message."Failed to store session.  ";
                                echo json_encode($obj);
                                exit;
                            }

    $query = 'DELETE FROM codes WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if($db->affected_rows >= 1){
                            $obj->message = $obj->message.'Codes: deleted.  ';
                            }else{
                            $obj->message = $obj->message.'Zero codes deleted  .  ';
                            }
    //signin page will redirect when it receives response with error=none
    echo json_encode($obj);
    exit;
 }

?>


