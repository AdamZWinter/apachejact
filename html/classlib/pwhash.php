<?php

class pwhash {

    public static function get64char($password){
        $pwhash= openssl_digest($password, "sha256");
        $pwhash = base64_encode($pwhash);
        $pwhash = str_replace('+', '', $pwhash);
        $pwhash = str_replace('/', '', $pwhash);
        $pwhash = substr($pwhash, 10, 64);
        return $pwhash;
    }//end function pwhash
    
}//end class pwhash
?>
