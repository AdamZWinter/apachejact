<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>decode.php</title>

<body>
<header>
</header>
</br>


<?php
echo 'This is disabled with an exit command at the top';
exit;

//mail2db.php

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);

require_once "/usr/local/lib/php/Mail.php";
require_once "/usr/local/lib/php/Mail/mimeDecode.php";
require_once "/var/www/html/classlib/directoryFunctions.php";

$mailfilesdir = '/var/www/reports/nobody/';
$nobodydir = '/var/www/reports/nobody/';
$badzipdir = '/var/www/reports/badzip/';
$noinsertdir = '/var/www/reports/noinsert/';
$processeddir = '/var/www/reports/processed/';

 $obj = new stdClass();
     $obj->datetime = $datetime;
     $obj->dateread = date("D M j G:i:s T Y");
     $obj->message = 'Msg: ';
     $obj->error = FALSE;

$mailfiles = listFilesOnly($mailfilesdir);
//var_dump($mailfiles);
//echo '<br>';
//echo '<br>';
$countfiles=0;
$onegoodfile=0;
$zipfiles=[];
$mailfileObjectsArray=[];
//var_dump($mailfiles);
foreach($mailfiles as $filename){
    //echo 'ForEach filename: '.$filename;
    echo '<br>';
    echo '<br>';
    $mailfileObj = new stdClass();
    $mailfileObj->filename = $filename;
    if ( !file_exists($filename) ) {
        error_log('File not found in mailfiles.  \n');
        continue;
    }else{
        $theFile = fopen($filename, "r");
    }
    if ( !$theFile ) {
        error_log('Failed to open theFile.  \n');
        continue;
    }else{
        $rawEmail = fread($theFile, filesize($filename));
        fclose($theFile);
        $onegoodfile++;
    }
    
    $args = [];
    $args['include_bodies'] = true;
    $args['decode_bodies'] = FALSE;
    $args['decode_headers'] = FALSE;
    //$args['rfc822_bodies'] = TRUE;  //this does nothing
    $objMail = new Mail_mimeDecode($rawEmail);
    $return = $objMail->decode($args);

    //$staticOnly = $args;
    //$staticOnly['input'] = $rawEmail;
    //$staticDecoded = Mail_mimeDecode::decode($staticOnly);
    //$xml = return_mimeDecode::getXML($return);
    //var_dump($xml);

    if (PEAR::isError($return)) {
        error_log('Failed to parse email.  \n');
        error_log($return->getMessage());
    } else {
        //echo("No error in PEAR::isError(return)");
    }
    
    //$mailasXML = $objMail->getXML($return);
    //var_dump($mailasXML);
    
    //echo '<br>';
    //echo '<br>';
    //echo '<br>';
    //echo $rawEmail;
    //echo '<br>';
    //echo '<br>';
    //echo '<br>';
    //echo '<pre>' . var_export($return, true) . '</pre>';
    //echo '<br>';
    //echo '<br>';
    //echo '<br>';
    //var_dump($return->parts);
    //echo '<pre>' . var_export($return->parts[1]->body, true) . '</pre>';
    //echo '<br>';
    //echo '<br>';
    //echo $return->parts[1]->body;
    
    if($return->parts[1]->body){
        $return->body = $return->parts[1]->body;
    }
    
    //echo '<br>';
    //echo '<br>';
    //echo $return->body;
    
    
    if($return->body){
        $decoded = base64_decode($return->body, true);
        
        if(!$decoded){
            //rename($filename, $badzipdir.basename($filename));                                //remove
            continue;
        }
        $datestring = date("YmdGis");
        $zipfile = '/var/www/reports/zip/'.$countfiles.$datestring;
        $zipfiles[]=$zipfile;
        $countfiles++;
        //echo $zipfile;

        $theZipFile = fopen($zipfile, "w");
        if ( !$theZipFile ) {
            //rename($filename, $badzipdir.basename($filename));                                 //remove
            error_log('Failed to open zipfile for writing.  '.PHP_EOL);
            continue;
        }    
        if(fwrite($theZipFile, $decoded)){
            $obj->message = $obj->message.'.'.PHP_EOL;
        }else{
            rename($filename, $badzipdir.basename($filename));
            error_log('Failed to write theZipFile.  '.PHP_EOL);
            $obj->message = $obj->message.'Failed to write theZipFile.  '.PHP_EOL;
            continue;
        }
        fclose($theZipFile);
    }//end if(body)
    else{
        //echo '<br>';
        //rename($filename, $nobodydir.basename($filename));                                     //remove
        continue;
    }//end else of if(body)
    
    $mailfileObj->zipfile = $zipfile;
    $mailfileObjectsArray[]=$mailfileObj;
    

}//end foreach(mailfiles as file)


if(!$onegoodfile){
    error_log('Not one good file. Exiting.');
    exit;
}

$xmldir = '/var/www/reports/xml/';

$mailfileObjectsArray2=[];
$xmlfiles=[];
//$number=0;
foreach($mailfileObjectsArray as $mailfileObj){
    if( $mailfileObj->filename && $mailfileObj->zipfile ){
        $filename = $mailfileObj->filename;
        $zipfile = $mailfileObj->zipfile;
        $zip = new ZipArchive;
        if ($zip->open($zipfile) === TRUE) {
            //$number++;
            //echo $number;
            $zip->extractTo($xmldir);
            $xmlfile = $zip->getNameIndex(0);
            $zip->close();
            //$xmlfilepathname = $xmldir.$xmlfile;
            //rename($xmldir.$xmlfile, $xmldir.basename($zipfile));  //do not do this in production
            $mailfileObj->xmlfile = $xmldir.$xmlfile;
            //$xmlfiles[]=$xmlfile;
            $mailfileObjectsArray2[]=$mailfileObj;
            
            //$mailfileObj2 = new stdClass();
            //$mailfileObj2->filename = $filename;
            
        } else {
            //rename($mailfileObj->filename, $badzipdir.basename($mailfileObj->filename));                  //remove
            error_log('Failed to open zipfile.  \n');
            $obj->message = $obj->message.'Failed to open zipfile.  \n';
        }
    }else{
        continue;
    }
}//end foreach mailfileObjectsArray

//var_dump($obj->message);
//echo '<br>';
//echo '<br>';
//var_dump($xmlfiles);

//$xmlfiles=[];
//$xmlfiles = listFilesOnly($xmldir);
//var_dump($xmlfiles);

require('/var/www/html/classlib/XMLreport.php');
require('/var/www/secrets/conf.php');

$db = new mysqli($dbserver, $dbuser, $userpw, $database);
if (mysqli_connect_errno()) {
        error_log('___Error: Could not connect to database.');
        exit;
    }else{
        //echo '___Successfully connected to database.';
    }

foreach($mailfileObjectsArray2 as $mailfileObj){
    $xfile = $mailfileObj->xmlfile;
    try{
        $xmlreport = new XMLreport($xfile);
        $xmlreport->read();
        //$return = $xmlreport->parse();
        //echo $return;
        if($xmlreport->parse()){
            $xmlreport->dump();
            continue;
            if($xmlreport->todb($db)){
                //success
                echo 'Success. ';
                rename($mailfileObj->filename, $processeddir.basename($mailfileObj->filename));
            }else{
                rename($mailfileObj->filename, $noinsertdir.basename($mailfileObj->filename));
            }
            //echo 'xmlreport parse is TRUE. ';
            //echo '<br>';
            //echo '<br>';
        }else{
            rename($mailfileObj->filename, $noinsertdir.basename($mailfileObj->filename));
            //echo 'xmlreport parse is FALSE. ';
            //echo '<br>';
        }
    } catch( Exception $e ){
        error_log('Caught exception: ',  $e->getMessage(), "\n");
        rename($mailfileObj->filename, $noinsertdir.basename($mailfileObj->filename));
    }

}//end foreach xmlfiles as xfile

error_log('Testing error log at end of mail2db.php.  Where is the error log?  ');

?>


</body>
</html>