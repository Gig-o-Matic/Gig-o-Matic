<?php
session_start();
include_once("settings.php");
include_once("debug.php");
include_once("nocache.php");
include_once("functions.php");

include_once("login.php");
check_login_state();

open_database_link();

$theId=0;
if (isset($_GET["id"])) {
  $theId=$_GET["id"];
}
if (isset($_POST["id"])) {
  $theId=$_POST["id"];
}
if ($theId) {
  $query="DELETE FROM event WHERE event_id=".$theId;
  $result = mysql_query($query);

  $query="DELETE FROM answer WHERE answer_event=".$theId;
  $result = mysql_query($query);
}

header("Location: index.php"); /* Redirect browser */
exit; // make sure the original page doesn't run
?>