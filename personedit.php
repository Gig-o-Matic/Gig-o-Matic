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
$page_title="Edit Person Details";
include("html_header.inc");
makepage();
include("html_footer.inc");
close_database_link();

function makepage()
{

  if ($_SESSION["error"]!="") {
    // there was an error updating, so deal with it
    echo($_SESSION["error"]);

    // read the values out of the $_SESSION variable
    $postvals=$_SESSION["postvals"];
    $thePerson=array();
    $thePerson["person_name"]=$postvals["name"];
    $thePerson["person_phone"]=$postvals["phone"];
    $thePerson["person_email"]=$postvals["email"];
    $theId=$postvals["id"];
    
    unset($_SESSION["postvals"]);
    unset($_SESSION["error"]);
  } else {
    // if no person selected, we're making a new person
    $theId=0;
    if (isset($_GET["id"])) {
      $theId=$_GET["id"];
    }
    if (isset($_POST["id"])) {
      $theId=$_POST["id"];
    }
    
    if ($theId) {
      $thePerson=person_from_id($theId);
    } else {
      $thePerson=array("person_name"=>"","person_phone"=>"","person_email"=>"");
    }
  }

  echo("<br><br>\n");
  echo("<form action=\"personupdate.php\" method=\"POST\">\n");
  echo("<input type=\"hidden\" name=\"id\" value=\"$theId\">\n");
  echo("Name: \n");
  echo("<input type=\"text\" name=\"name\" value=\"".$thePerson["person_name"]."\"><br>\n");
  echo("Phone: ");
  echo("<input type=\"text\" name=\"phone\" value=\"".$thePerson["person_phone"]."\"><br>\n");
  echo("Email: ");
  echo("<input type=\"text\" name=\"email\" value=\"".$thePerson["person_email"]."\"><br>\n");

  echo("Instrument: \n");
  $allInstruments=get_all_instruments();
  echo("<select name=\"instrument\">\n");
  foreach ($allInstruments as $instId => $instName) {
    echo("<option value=".$instId);
    if ($instId==$thePerson["person_instrument"]) {
      echo(" SELECTED");
    }
    echo(">".$instName."<br>\n");
  }
  echo("</select>\n");

  echo("<br><br>\n");

  if ($theId) {
    echo("<INPUT TYPE=\"submit\" value=\"update\">\n");
    echo("<a href=\"person.php?id=".$theId."\">cancel</a><br><br>\n");

    echo("<div id=\"deletelink\">\n");
    echo("<a href=\"javascript:ShowContent('deleteconfirm');HideContent('deletelink');\">delete person</a>\n");
    echo("</div>\n"); // deletelink
    echo("<div id=\"deleteconfirm\" style=\"display:none\">\n");
    echo("really delete this person? <a href=\"deleteperson.php?id=".$theId."\">yes!</a><br>\n");
    echo("</div>\n"); // deletelink
  } else {
    echo("<INPUT TYPE=\"submit\" value=\"create\">\n");
    echo("<a href=\"index.php\">cancel</a><br>\n");
  }

  echo("</form>\n");
}

?>