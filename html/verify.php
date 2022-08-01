<?php
//verify.php
require('header.php');
?>

<?php

if(!($code=@$_GET["code"])){$obj->error = 'Bad link no code.';echo json_encode($obj);exit;}
else {$code=$_GET["code"];}

$obj->code = $code;
$message = 'Message:  ';

//check if code exists
$authorized=FALSE;  //force this back from header so that it has to check
$checkcode = bin2hex(random_bytes(64));   //initialized
$email = bin2hex(random_bytes(64));   //initialized
$initialize = bin2hex(random_bytes(64));   //initialized

$query = "SELECT email, code, initialize
          FROM codes WHERE code = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $code);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($emaildb, $codedb, $initializedb);
if (mysqli_connect_errno()) {$obj->error = 'Error: Could not SELECT';
                            echo json_encode($obj);
                            exit;
}
else{
    if($stmt->num_rows == 1) {
         while($stmt->fetch()){
            $checkcode = $codedb;
            $email = $emaildb;
            $initialize = $initializedb;
         }
                 if(strcmp($code, $checkcode)==0){
                              $authorized=TRUE;
                              $obj->message=$obj->message.'Authorized!  ';
                  }
    } elseif($stmt->num_rows == 0) {
         $message=$message.'No code match.  ';
    } else {
         $obj->error = 'Database Error';
    }
}


if ($authorized){
    
    $query = 'INSERT INTO sessions VALUES (?, ?, ?)';
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssi', $email, $session, $datetime);
    $stmt->execute();
    if($db->affected_rows == 1){
                            $obj->message = $obj->message."Sessions: 1 row affected.  ";
                            }else{
                            $obj->message = $obj->message."Failed to store session.  ".$email;
                            echo "<p>Failed to store session.  Please contact support </p>";
                            error_log(json_encode($obj), 3, $logFile);
                            exit;
                            }

    
    $query = 'DELETE FROM codes WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if($db->affected_rows == 1){
                            $obj->message = $obj->message.'Codes: deleted.  ';
                            }else{
                            $obj->message = $obj->message.'Failed to delete code.  '.$email;
                            error_log(json_encode($obj));
                            }
  
    $verified='yes';                                                                 
    $query = 'UPDATE users SET verified = ? WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $verified, $email);
    $stmt->execute();
    if($db->affected_rows == 1){
                            $obj->message = $obj->message.'verified updated to yes.  ';
                            }else{
                            $obj->message = $obj->message.'Failed to set verified email.  '.$email;
                            echo "Failed to set verified.  Please contact support ";
                            error_log(json_encode($obj));
                            exit;
                            }

    $utilities=0;
    $query = 'INSERT INTO privileges VALUES (?, ?)';
    $stmt = $db->prepare($query);
    $stmt->bind_param('si', $email, $utilities);
    $stmt->execute();
    if($db->affected_rows == 1){
                            $obj->message = $obj->message."Privileges: 1 row affected.  ";
                            }else{
                            $obj->message = $obj->message."Failed to create user in privileges   ".$email;
                            echo "<p>Failed to create user in privileges.  Please contact support </p>";
                            error_log(json_encode($obj));
                            exit;
                            }


}

$active = 'no';
if(strcmp($initialize, 'yes')==0){
    
    //Initialize any user settings here
    $placeholder = 'placeholder';
    $integer = 000;
/*    
    $query = 'INSERT INTO tokens VALUES (?, ?, ?, ?, ?, ?, ?)';
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssiisss', $email, $placeholder, $integer, $integer, $placeholder, $placeholder, $placeholder);
    $stmt->execute();
    if($db ->affected_rows == 1){
                            $obj->message = $obj->message."tokens: 1 row affected.  ";
                            }else{
                            $obj->message = $obj->message."Failed to store tokens initialization.  ".$email;
                            echo "Failed to store tokens initialization.  Please contact support ";
                            error_log(json_encode($obj));
                            exit;
                            }
*/
}

$return2Login = '
<p>
This code is expired or does not exist.  Please <a href="'.$webRoot.'/signin.php">sign in </a>again.
</p>
';

if(!$authorized){echo $return2Login; require('/var/www/html/footer.php'); exit;}

//----------------------------------- Start Authorized Content ----------------------------------------------------
?>

    <script type="text/javascript">
    window.location.replace("/dashboard/session.php");
    </script>


<?php
require('footer.php');
?>
