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
    $array=scandir($filename);
    foreach($array as $value){
    echo $value."\n";
    }
}else{echo 'What did you do wrong?';}

copy refresh paste then save

?>
