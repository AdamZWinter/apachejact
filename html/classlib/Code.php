<?php

class Code {

    public static function get16chars(){
        $forcode=random_bytes(16);
        $codehash = openssl_digest($forcode, "sha256");
        $codehash = base64_encode($codehash);
        $codehash = str_replace('+', '', $codehash);
        $codehash = str_replace('/', '', $codehash);
        $code = substr($codehash, 10, 16);
        return $code;
    }//end get16chars

}//end class Code

?>