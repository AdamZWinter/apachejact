<?php
require('/var/www/html/classlib/loggedinUser.php');  //includes conf
require('/var/www/html/classlib/Privileges.php');

$obj = new stdClass();
     $obj->datetime = $datetime;
     $obj->dateread = date("D M j G:i:s T Y");
     $obj->message = '';
     $obj->error = 'none';

if(!Privileges::granted($email, 'utilities')){$obj->error = 'You are not authorized to do this.  ';echo json_encode($obj);exit;}

if(!isset($_POST["filename"])){$obj->error = 'No filename included.';echo json_encode($obj);exit;}
else {$filename=$_POST["filename"];}

if(!isset($_POST["password"])){$obj->error = 'What did you do wrong?';echo json_encode($obj);exit;}
else {$password=$_POST["password"];}

require('/var/www/html/classlib/pwhash.php');
$pwhash=pwhash::get64char($password);

if($pwhash==$pw){
$theFile = fopen($filename, "r") or die("Unable to open file!");
echo fread($theFile, filesize($filename));
fclose($theFile);
}else{echo 'What did you do wrong?';}

?>
