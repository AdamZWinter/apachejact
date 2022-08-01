<?php
//Authorization.php

class Authorization {
    public static function emailAuthorization($db, $obj, $session){
        
 $result = new stdClass();
     $result->email = '';
     $result->authorized = FALSE;

 $query = "SELECT email, sessionid
           FROM sessions WHERE sessionid = ?";
 $stmt = $db->prepare($query);
 $stmt->bind_param('s', $session);
 $stmt->execute();
 $stmt->store_result();
 $stmt->bind_result($emaildb, $sessiondb);
 if (mysqli_connect_errno()) {$obj->error = 'Error: Could not connect to database.  ';
                             echo json_encode($obj);
                             error_log(json_encode($obj));
                             exit;
 }
 else{
     if($stmt->num_rows == 1) {
          while($stmt->fetch()){
             $email = $emaildb;
             $checksession = $sessiondb;
          }
                  if(strcmp($session, $checksession)==0){
                               $result->authorized = TRUE;
                               $obj->message=$obj->message.'Authorized!  ';
                   }
     } elseif($stmt->num_rows == 0) {
          $obj->message=$obj->message.'Session not found.  ';
     } else {
          $obj->error = 'Database Error: Sessions not 1 or 0.  ';
         error_log(json_encode($obj));
         echo json_encode($obj);
         exit;
    }
}
        
        if($result->authorized){
            return $result;
        }else{
            return FALSE;
        }
        

}}  // end getemail class and getemail function

?>