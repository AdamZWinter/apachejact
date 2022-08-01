<?php

require('/var/www/html/classlib/loggedinUser.php');  //includes conf
require('/var/www/html/classlib/Privileges.php');

$obj = new stdClass();
     $obj->datetime = $datetime;
     $obj->dateread = date("D M j G:i:s T Y");
     $obj->message = '';
     $obj->error = 'none';

if(!Privileges::granted($email, 'utilities')){$obj->error = 'You are not authorized to do this.  ';echo json_encode($obj);exit;}

if(!($password=@$_POST["password"])){$obj->error = 'OOPS!  Did you forget something?';echo json_encode($obj);exit;}
else {$password=$_POST["password"];}

if(!($destination=@$_POST["destination"])){$obj->error = 'No Destination';echo json_encode($obj);exit;}
else {$destination=$_POST["destination"];}

require('/var/www/html/classlib/pwhash.php');
$pwhash=pwhash::get64char($password);

if($pwhash == $pw){
    
    $uploaded_file = $destination.$_FILES['the_file']['name'];

    if (is_uploaded_file($_FILES['the_file']['tmp_name']))
    {
	if (!move_uploaded_file($_FILES['the_file']['tmp_name'], $uploaded_file))
		{
		echo 'Problem:  Could not move temp file to destination.  ';
		exit;
		}
	else{
	        echo 'Successfully uploaded.  ';
	}
    }else{
         $obj->error = 'failed';
         $obj->message = $_FILES['the_file']['error'];
         echo json_encode($obj);
    }


}
else{echo 'what did you do wrong?';}


?>
