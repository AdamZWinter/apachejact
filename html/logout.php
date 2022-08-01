<?php
//logout.php
require('/var/www/html/header.php');

    $query = 'DELETE FROM sessions WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if($db->affected_rows != 0){
                            $obj->message = $obj->message.'Session deleted.  ';
                            }else{
                            $obj->message = $obj->message.'0 rows affected in sessions  ';
                            }

$_SESSION = array();
session_destroy();

?>


<script>    
        window.location.href = "index.php";
</script>


<?php
require('footer.php');
?>
