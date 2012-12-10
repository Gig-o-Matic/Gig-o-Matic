<?php
session_start();
include_once("settings.php");
include_once("debug.php");
include_once("nocache.php");
include_once("functions.php");

include_once("login.php");
check_login_state();

open_database_link();
global $page_title;
$page_title="Edit Event Details";
include("html_header.inc");
makepage();
include("html_footer.inc");
close_database_link();

function makepage()
{
	// The message
	$message = "Line 1\nLine 2\nLine 3";
	
	// In case any of our lines are larger than 70 characters, we should use wordwrap()
	$message = wordwrap($message, 70);
	
	$headers = 'From: aoppenheimer@daktaridx.com' . "\r\n" .
    'Reply-To: aoppenheimer@daktaridx.com.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
	// Send
	mail('aoppenheimer@gmail.com', 'My Subject', $message, $headers);
}

exit;

?>