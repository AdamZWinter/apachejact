<?php

function listFilesOnly($dir){
           
    $phperrors = '/var/www/logs/phperrors.log';
    
    $dir = rtrim($dir,"/");  //Remove trailing slash
    //echo $dir;
    //echo '<br>';
    $directory = scandir($dir);
    $arrayFilesOnly=[];

    foreach ($directory as $file) {
        if($file == '..'){
        }
        elseif($file == '.') {
        }
        elseif(is_dir($dir.'/'.$file)) {
        }
        elseif($file == '.gitkeep') {
            //error_log('skipping .gitkeep  \n', 3, $phperrors);
        }
        else {
            $arrayFilesOnly[]=$dir.'/'.$file;
        }
    }//end foreach directory
    
    return $arrayFilesOnly;

}

?>