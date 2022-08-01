<?php
// /var/www/html/classlib/Privileges
// call on this class when certain functions need required privileges
// privileges are established by the email of the of the logged in user as established in the header
// privilages are granted in privileges table of database:  request value is equal to table column name

class Privileges {
    
    public static function granted($email, $request){
        require('/var/www/secrets/conf.php');
        
         $obj = new stdClass();
         $obj->message = '';
         $obj->dateread = date("D M j G:i:s T Y");
        
         $db = new mysqli($dbserver, $dbuser, $userpw, $database);
         if (mysqli_connect_errno()) {
                                       $obj->error = 'Error: Could not connect to database.';
                                       error_log(json_encode($obj));
                                       exit;
                                     }else{
                                       $obj->message = $obj->message.'Successfully connected to database.  ';
                                     }

         $query = "SELECT {$request}
                   FROM privileges
                   WHERE email = ?";
         $stmt = $db->prepare($query);
         $stmt->bind_param('s', $email);
         $stmt->execute();
         $stmt->store_result();
         $stmt->bind_result($bool);
         if (mysqli_connect_errno()) {$obj->error = 'Error: Could not connect to database.  ';
                                     error_log(json_encode($obj));
                                     exit;
         }
         else{
             if($stmt->num_rows == 1) {
                  while($stmt->fetch()){
		     
                      if($bool == 1){
                          return true;
                          exit;
                      }else{
                          return false;
                          exit;
                      }
                  }
             } elseif($stmt->num_rows == 0) {
                  return false;
                  exit;
             } else {
                  $obj->error = 'Database Error: Sessions not 1 or 0.  ';
                  error_log(json_encode($obj));
                  return false;
                  exit;
            }
        }

    $db->close();


    } // end granted function

} // end Privileges class

?>
