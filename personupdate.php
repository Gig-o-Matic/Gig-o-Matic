<?php
session_start();
include_once("settings.php");
include_once("functions.php");

// take info from the POST and update or create a person

// first validate that we have what we need; if not, just back to the edit form
$errormsg="";
if ($_POST["name"] == "") {
  $errormsg .= "Name is required!<br>";
}

if ($errormsg != "") {
  $_SESSION["postvals"]=$_POST;
  $_SESSION["error"]=$errormsg;
  header("Location: personedit.php"); /* Redirect browser */

  /* Make sure that code below does not get executed when we redirect. */
  exit;
}

open_database_link();

if ($_POST["id"]==0) {
  // creating a new person
  $query="insert into person set ";
} else {
  // update person
  $query="update person set ";
}

$query .= "person_name=\"".$_POST["name"]."\","
."person_phone=\"".$_POST["phone"]."\","
."person_email=\"".$_POST["email"]."\","
."person_instrument=".$_POST["instrument"];


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
  $query .= " where person_id=".$theId;
  mysql_query($query);
}
close_database_link();

header("Location: person.php?id=$theId"); /* Redirect browser */

/* Make sure that code below does not get executed when we redirect. */
exit;
?>