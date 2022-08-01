<?php

//This did not work with auth:587 when it was tested, but does work without auth 25

require_once "Mail.php";
//require_once "Mail/mime.php";    //only needed for attachements

$headers = [
	"From" => "noreply@topsecondhost.com",
	"To" => "adam@emberone.com",
	"Reply-To" => "noreply@topsecondhost.com",
	"Subject" => "Test Email",
	"Content-Type" => "text/html"
];

$stmp = Mail::factory(
	"smtp",
	[
		"host" => "mimosa",
		"username" => "noreply",
		"password" => "GXJGGHKgkhjxvYAawlS9FpKh2NZFlW",
		"port" => 25,
		"auth" => false,
		"html" => true,
        "debug" => true
	]
);

$body = '
	<h1>This is a test email</h1>
	<p>This is to test HTML within our emails.</p>
';

$mail = $stmp->send(
	"adam@emberone.com",
	$headers,
	$body
);

var_dump($mail);

?>