<?php
require('/var/www/html/authorizedHeader.php');
require('/var/www/html/classlib/Privileges.php');

$obj = new stdClass();
     $obj->datetime = $datetime;
     $obj->dateread = date("D M j G:i:s T Y");
     $obj->message = '';
     $obj->error = 'none';

if(!Privileges::granted($email, 'utilities')){$obj->error = 'You are not authorized to do this.  ';echo json_encode($obj);exit;}

//----------------------------------- Start Authorized Content ----------------------------------------------------
?>

<p>
<form action="upload.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
<label for="the_file">Upload a file:</label>
<input type="file" name="the_file" id="the_file" />
<input type="password" name="password" id="password" style="width:200px" />
<br>
<br>
<label for="destination">Destination (ends with /):</label><input type="text" name="destination" id="destination" style="width:350px;" value="/var/www/html/uploads/" />
<input type="submit" value="Upload File" />
</form>
</p>




<?php
require('/var/www/html/footer.php');
?>


