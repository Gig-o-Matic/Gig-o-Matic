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
$page_title="Gig Detail";
include("html_header.inc");
makepage();
include("html_footer.inc");
close_database_link();


function makepage()
{
  
  $theId=0;
  if (isset($_GET["id"])) {
    $theId=$_GET["id"];
  }

  if (isset($_POST["id"])) {
    $theId=$_POST["id"];
  }

  // if no project selected, bail out now
  if ($theId==0) {
    return;
  }

  $line=event_from_id($theId);
  $is_old=is_old_event($line);

  echo("<h1>".$line["event_name"]);
  echo("</h1>");
  echo("<table cellspacing=0 cellpadding=0>\n");

  echo("<tr>\n");
  echo("<td class=\"eventheadercell\">Date</td>\n");
  echo("<td class=\"eventinfocell\">".format_date_for_display($line["event_date"])."</td>\n");
  echo("</tr>\n");
 
  echo("<tr>\n");
  echo("<td class=\"eventheadercell\">Call Time</td>\n");
  $tmp= ($line["event_call"]=="")?"?":$line["event_call"];
  echo("<td class=\"eventinfocell\">".$tmp."</td>\n");
  echo("</tr>\n");

  echo("<tr>\n");
  echo("<td class=\"eventheadercell\">Confirmed?</td>");
  echo("<td class=\"eventinfocell\">".confirmed_from_id($line["event_confirmed"])."</td>\n");
  echo("</tr>\n");

  echo("<tr>\n");
  echo("<td class=\"eventheadercell\">Contact</td>\n");
  if ($is_old) {
	  echo("<td class=\"eventinfocell\">".$line["person_name"]."</td>\n");
  } else {
	  echo("<td class=\"eventinfocell\">".make_person_link($line)."</td>\n");
  }
  echo("</tr>\n");

  echo("<tr>\n");
  echo("<td class=\"eventheadercell\">Details</td>");
  echo("<td class=\"eventinfocell\"><pre>\n".$line["event_details"]."\n</pre></td>");
  echo("</tr>\n");

  echo("</table>\n");

	if (!$is_old) {
		echo("<a href=eventedit.php?id=".$theId.">[edit gig]</a>");
	}

  echo("<br><br>");

  echo("<table cellspacing=0 cellpadding=0>\n");

  $currentInstrument=-1;
  
  $allPeople=get_all_people($is_old);

  while($person=mysql_fetch_assoc($allPeople)) {
	if ($person["person_instrument"] <> $currentInstrument) {
	  echo("<tr>\n");
	  echo("<td class=\"eventinstrumentcell\"><br>");
	  echo(instrument_from_id($person["person_instrument"]));
	  echo("</td></tr>\n");
	  $currentInstrument=$person["person_instrument"];
	}
	echo("<tr>");
	echo("<td class=\"eventpersoncell\">");
	if ($is_old) {
		echo($person["person_name"]);
	} else {
		echo(make_person_link($person));
	}
	echo("</td>\n");

	echo("<td class=\"eventanswercell\">");
	$theAnswer=get_answer($person["person_id"],$theId);
	if (mysql_num_rows($theAnswer)==0) {
	  echo("-");
        } else {
	  $answer=mysql_fetch_assoc($theAnswer);
	  echo(answer_from_id($answer["answer_answer"]));
	  echo("</td>\n");
	  echo("<td>");
	  echo($answer["answer_comments"]);
	  echo("</td>");
	}

	echo("</tr>\n");
  }
  echo("</table>\n");

}


?>