<?php
session_start();
include_once("settings.php");
include_once("functions.php");
// take info from the POST and update or create a project

// first validate that we have what we need; if not, just back to the edit form
$errormsg="";
if ($_POST["name"] == "") {
  $errormsg .= "Project name is required!<br>";
}

if ($_POST["date_month"] == "") {
  $errormsg .= "Month is required!<br>";
}
if ($_POST["date_day"] == "") {
  $errormsg .= "Day is required!<br>";
}
if ($_POST["date_year"] == "") {
  $errormsg .= "Year is required!<br>";
}

if ($_POST["contact"]==0) {
  $errormsg .= "A contact person is required<br>";
}

if ($errormsg != "") {
  $_SESSION["postvals"]=$_POST;
  $_SESSION["error"]=$errormsg;
  header("Location: eventedit.php"); /* Redirect browser */

  /* Make sure that code below does not get executed when we redirect. */
  exit;
}

open_database_link();

if ($_POST["id"]==0) {
  // creating a new project
  $query="insert into event set ";
} else {
  $query="update event set ";
}

$query .= "event_name=\"".$_POST["name"]."\","
."event_call=\"".$_POST["call"]."\","
."event_details=\"".$_POST["details"]."\","
."event_confirmed=".$_POST["confirmed"].","
."event_contact=".$_POST["contact"].","
."event_date=\"".format_date_for_database($_POST["date_month"],$_POST["date_day"],$_POST["date_year"])."\"";
// that date stuff is a hack but we're using an old SQL server.

$theId=0;
if ($_POST["id"]==0) {
  mysql_query($query);
  $id=mysql_insert_id();
  if ($id == 0) {
    die("Could not insert!");
  } else {
    $theId=$id;
  }
} else {
  $theId=$_POST["id"];
  $query .= " where event_id=".$theId;
  $success=mysql_query($query);
}

close_database_link();

header("Location: event.php?id=$theId"); /* Redirect browser */

/* Make sure that code below does not get executed when we redirect. */
exit;

?>