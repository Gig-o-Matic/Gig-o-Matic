<?php
session_start();
include_once("settings.php");
include_once("functions.php");
open_database_link();
switch($_REQUEST["action"]) {
    case "answer":
	$personId=$_REQUEST["personId"];
	$eventId=$_REQUEST["eventId"];
	$theAnswer=$_REQUEST["theAnswer"];
	$theComments=$_REQUEST["theComments"];
	update_answer($personId,$eventId,$theAnswer,$theComments);
	echo("foo|updating ".$personId." ".$eventId." ".$theAnswer." >".$theComments."<");
      break;
}
close_database_link();
?>