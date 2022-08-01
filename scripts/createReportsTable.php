#!/usr/local/bin/php
<?php

$date = date('U');

//echo 'This is the date: '.$date;

require('/var/www/secrets/conf.php');

//echo 'Trying database';
$db = new mysqli($dbserver, $dbuser, $userpw, $database);
if ($mysqli_connection->connect_error) {
   echo "___Not connected, error: " . $mysqli_connection->connect_error;
}
else {
   echo "___Connected." . $mysqli_connection->connect_error;
}
if (mysqli_connect_errno()) {
                             echo '___Error: Could not connect to database.';
                             exit;
                             }else{
                             echo '___Successfully connected to database.';
                             }

$query= "CREATE TABLE spfreports (
reportid VARCHAR(64) NOT NULL,
domain VARCHAR(128) NOT NULL,
orgname VARCHAR(64) NOT NULL,
start INT(64) NOT NULL,
end INT(64) NOT NULL,
sourceip VARCHAR(32) NOT NULL,
count INT(8) NOT NULL,
disposition VARCHAR(32) NOT NULL,
dkimalign VARCHAR(16) NOT NULL,
spfalign VARCHAR(32) NOT NULL,
headerfrom VARCHAR(128) NOT NULL,
dkimdomain VARCHAR(128) NOT NULL,
dkimresult VARCHAR(16) NOT NULL,
selector VARCHAR(64) NOT NULL,
spfdomain VARCHAR(128) NOT NULL,
spfresult VARCHAR(32) NOT NULL,
dateread VARCHAR(64) NOT NULL,
randomid VARCHAR(16) NOT NULL PRIMARY KEY
)";
$stmt = $db->prepare($query);
$stmt->execute();

$db->close();



echo '\nTables created?  Check on phpMyAdmin.';

?>
