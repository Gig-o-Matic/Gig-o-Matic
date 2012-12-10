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

  if ($_SESSION["error"]!="") {
    // there was an error updating, so deal with it
    echo("<br><br><br><b>".$_SESSION["error"]."</b><br>");

    // read the values out of the $_SESSION variable
    $postvals=$_SESSION["postvals"];
    $theEvent=array();
    $theEvent["event_name"]=$postvals["name"];
    $theEvent["event_date_month"]=$postvals["date_month"];
    $theEvent["event_date_day"]=$postvals["date_day"];
    $theEvent["event_date_year"]=$postvals["date_year"];
    $theEvent["event_call"]=$postvals["call"];
    $theEvent["event_details"]=$postvals["details"];
    $theEvent["event_confirmed"]=$postvals["confirmed"];
    $theEvent["event_contact"]=$postvals["contact"];
    $theId=$postvals["id"];
    unset($_SESSION["postvals"]);
    unset($_SESSION["error"]);
  } else {
    // if no event selected, we're making a new event
    $theId=0;
    if (isset($_GET["id"])) {
      $theId=$_GET["id"];
    }
    
    if (isset($_POST["id"])) {
      $theId=$_POST["id"];
    }
    
    if ($theId) {
      $theEvent=event_from_id($theId);
  $theDate=parse_date($theEvent["event_date"]);
    $theEvent["event_date_month"]=$theDate["month"];
    $theEvent["event_date_day"]=$theDate["day"];
    $theEvent["event_date_year"]=$theDate["year"];
    } else {
	  $today=getdate();
      $theEvent=array ("event_name"=>"","event_date_month"=>$today["mon"],"event_date_day"=>$today["mday"],"event_date_year"=>$today["year"],"event_call"=>"","event_details"=>get_default_event_details(),"event_confirmed"=>0,"event_contact"=>0);
    }
  }

  echo("<br><br>\n");
  echo("<form action=\"eventupdate.php\" method=\"POST\">\n");
  echo("<input type=\"hidden\" name=\"id\" value=\"".$theId."\">\n");
  echo("Event name: \n");
  echo("<input type=\"text\" name=\"name\" value=\"".$theEvent["event_name"]."\"> (required)<br>\n");

  echo("Date: \n");
//  $theDate=parse_date($theEvent["event_date"]);
//  echo("Month: <input type=\"text\" name=\"date_month\" value=\"".$theEvent["event_date_month"]."\"> <br>\n");
//  echo("Day: <input type=\"text\" name=\"date_day\" value=\"".$theEvent["event_date_day"]."\"> <br>\n");
//  echo("Year: <input type=\"text\" name=\"date_year\" value=\"".$theEvent["event_date_year"]."\"> <br>\n");

  echo("<select name=\"date_month\">\n");
  for($i=1; $i<=12; $i++) {
    echo("<option value=\"".$i."\"");
    if ($i == $theEvent["event_date_month"]) {
      echo(" SELECTED");
    }
    echo(">".month_from_number($i)."\n");
  }
  echo("</select>\n");

  echo("<select name=\"date_day\">\n");
  for($i=1; $i<=31; $i++) {
    echo("<option value=\"".$i."\"");
    if ($i == $theEvent["event_date_day"]) {
      echo(" SELECTED");
    }
    echo(">".$i."\n");
  }
  echo("</select>\n");

  $theDate=getdate();
  echo("<select name=\"date_year\">\n");
  for($i=0; $i<=2; $i++) {
    echo("<option value=\"".($theDate["year"]+$i)."\"");
    if ($theEvent["event_date_year"]==($theDate["year"]+$i)) {
      echo(" SELECTED");
    }
    echo(">".($theDate["year"]+$i)."\n");
  }
  echo("</select><br>\n");



  echo("Call Time: \n");
  echo("<input type=\"text\" name=\"call\" value=\"".$theEvent["event_call"]."\"><br>\n");

  echo("Event Details:<br>\n");
//  echo("<input type=\"text\" name=\"details\" value=\"".$theEvent["event_details"]."\"><br>\n");
  echo("<textarea name=\"details\" rows=10 cols=60>\n");
  echo($theEvent["event_details"]);
  echo("</textarea><br>\n");

  echo("Band Contact: ");
  $allPeople=get_all_people_alpha();
  echo("<select name=\"contact\">\n");
  echo("<option value=\"0\"");
  if ($theEvent[event_contact]==0) {
    echo(" SELECTED");
  }
  echo("> (none selected)<br>\n");

  while($person=mysql_fetch_assoc($allPeople)) {
    echo("<option value=\"".$person["person_id"]."\"");
    if ($person["person_id"]==$theEvent[event_contact]) {
      echo(" SELECTED");
    }
    echo("> ".$person["person_name"]."<br>\n");
  }
  echo("</select><br>\n");

  echo("Confirmed?: \n");
  $allConfirmed=get_all_confirmed();
  echo("<select name=\"confirmed\">\n");
  foreach ($allConfirmed as $statId => $statName) {
    echo("<option value=".$statId);
    if ($statId==$theEvent["event_confirmed"]) {
      echo(" SELECTED");
    }
    echo("> ".$statName."<br>\n");
  }
  echo("</select>\n");

  echo("<br><br>\n");

  if ($theId) {
    echo("<INPUT TYPE=\"submit\" value=\"update\">\n");
    echo("<a href=\"event.php?id=".$theId."\">cancel</a><br><br>\n");
   
    echo("<div id=\"deletelink\">\n");
    echo("<a href=\"javascript:ShowContent('deleteconfirm');HideContent('deletelink');\">delete gig</a>\n");
    echo("</div>\n"); // deletelink
    echo("<div id=\"deleteconfirm\" style=\"display:none\">\n");
    echo("really delete this gig? <a href=\"deleteevent.php?id=".$theId."\">yes!</a><br>\n");
    echo("</div>\n"); // deletelink
  } else {
    echo("<INPUT TYPE=\"submit\" value=\"create\">\n");
    echo("<a href=\"index.php\">cancel</a><br>\n");
  }

  echo("</form>\n");
}

?>