<?php

require('/var/www/html/classlib/loggedinUser.php');  //includes conf
require('/var/www/html/classlib/Privileges.php');

$obj = new stdClass();
     $obj->datetime = $datetime;
     $obj->dateread = date("Y-m-d-H-i-s");
     $obj->message = '';
     $obj->error = 'none';

if(!Privileges::granted($email, 'utilities')){$obj->error = 'You are not authorized to do this.  ';echo json_encode($obj);exit;}

if(!($filename=@$_POST["filename"])){$obj->error = 'No filename included.';echo json_encode($obj);exit;}
else {$filename=$_POST["filename"];}

if(!($contents=@$_POST["contents"])){$obj->error = 'No contents included.';echo json_encode($obj);exit;}
else {$contents=$_POST["contents"];}

if(!($password=@$_POST["password"])){$obj->error = 'No password included.';echo json_encode($obj);exit;}
else {$password=$_POST["password"];}

require('/var/www/html/classlib/pwhash.php');
$pwhash=pwhash::get64char($password);

if($pwhash == $pw){

$oldVersion='/var/www/versions/'.$obj->dateread.basename($filename);
copy($filename, $oldVersion);

try
    {
      $theFile = fopen($filename, "w");
      if ( !$theFile ) {
        throw new Exception('Failed to open the file.  ');
      }    
    
    
  
    if(fwrite($theFile, $contents)){echo 'Saved. Revision history is saved in versions';}else{echo 'Failed to write.  ';}
      
      fclose($theFile);

    } catch ( Exception $e ) {
      echo 'Failed.  ';
    } 



}
else{echo 'what did you do wrong?';}




?>
