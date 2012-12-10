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
$page_title="G I G - O - M A T I C";
include("html_header.inc");
makepage();
include("html_footer.inc");
close_database_link();


function makepage()
{

  $theDate=getdate();
  $theMonth=$theDate["mon"];

  if (isset($_GET["month"])) {
    $theMonth=$_GET["month"];
  }
  if (isset($_POST["month"])) {
    $theMonth=$_POST["month"];
  }

  $theYear=$theDate["year"];

  if (isset($_GET["year"])) {
    $theYear=$_GET["year"];
  }
  if (isset($_POST["year"])) {
    $theYear=$_POST["year"];
  }

  if ($theMonth==0) {
    $theMonth=12;
    $theYear--;
  } elseif ($theMonth==13) {
    $theMonth=1;
    $theYear++;
  }

  $allEvents=get_all_future_events_for_month($theMonth,$theYear);
  $gigsOnPage=mysql_num_rows($allEvents);

  echo("<br><br>");

  echo("<table cellspacing=0 cellpadding=0>\n");
  echo("<tr>\n");
  echo("<td><img src=\"images/ulcorner.gif\" height=10 width=10></td>\n");
  echo("<td width=300 colspan=".(2*$gigsOnPage)." class=\"bordercell\"></td>\n");
  echo("<td align=right><img src=\"images/urcorner.gif\" height=10 width=10></td>\n");
  echo("</tr>");

  echo("<tr>\n");
  echo("<td class=\"bordercell\"></td>\n"); // first column, for decoration

  echo("<td colspan=".(2*$gigsOnPage)." class=\"titlecell\">");
  echo("Upcoming Gigs:&nbsp;&nbsp;&nbsp;&nbsp;");

  echo("<a href=\"index.php?month=".($theMonth-1)."&year=".$theYear."\">");
  echo("<img src=\"images/leftarrow.gif\" border=0 class=middle>");
  echo("</a>");
  echo(month_from_number($theMonth)."&nbsp;".$theYear);
  echo("<a href=\"index.php?month=".($theMonth+1)."&year=".$theYear."\">");
  echo("<img src=\"images/rightarrow.gif\" border=0 class=middle>");
  echo("</a>");

  echo("</td>\n");

  echo("<td class=\"bordercell\"></td>\n");
  echo("</tr>");

  echo("<tr>\n"); // gig line
  echo("<td class=\"leftborderstripe\"></td>\n"); // first column, for decoration
  echo("<td width=100></td>\n"); // next column, for people
  
  $theEventCount=0;
  $theOffsetCount=0;
  $theEventIds=array();
  while($event=mysql_fetch_assoc($allEvents)) {
	  echo("<td class=\"gigcell\">");
	  echo("<br>".format_date_for_short_display($event["event_date"])."<br><br>\n");
	  echo(make_event_link($event));
	
	  if ($event["event_confirmed"]>0) {
		echo("<br><br><span class=\"confirmedspan\">".confirmed_from_id($event["event_confirmed"])."</span><br>\n");
	  }
	  echo("</td>\n");
          array_push($theEventIds,$event["event_id"]);
	  $theEventCount++;

	  if ($theEventCount<$gigsOnPage) {
	    echo("<td class=\"borderstripe\" width=2></td>\n");
	  }

  }
  echo("<td class=\"rightborderstripe\"></td>\n");
  echo("</tr>");
  
  if ($gigsOnPage>0) {
    $allPeople=get_all_people();

  $currentInst=-1;
  while($person=mysql_fetch_assoc($allPeople)) {
    if ($currentInst <> $person["person_instrument"]) {
      echo("<tr>\n");
      echo("<td class=\"leftborderstripe\"></td>\n");
      echo("<td class=\"instrumentcell\"><br>".instrument_from_id($person["person_instrument"])."</td>\n");

      for($i=0; $i<count($theEventIds); $i++) {
	echo("<td></td>");
	if ($i < ($gigsOnPage-1)) {
	  echo("<td class=\"borderstripe\"></td>\n");
	}
      }

      echo("<td class=\"rightborderstripe\"></td>\n");
      echo("</tr>\n");
      $currentInst=$person["person_instrument"];
    }
    echo("<tr>\n");
    echo("<td class=\"leftborderstripe\"></td>\n");
    echo("<td class=\"personcell\">".make_person_link($person)."</td>\n");

    for($i=0; $i<count($theEventIds); $i++) {
      $tmpAnswer=get_answer($person["person_id"],$theEventIds[$i]);
      if (mysql_num_rows($tmpAnswer)>0) {
	$answer=mysql_fetch_assoc($tmpAnswer);
 	echo("<td class=\"contentcell\">\n");

//	echo(answer_from_id($answer["answer_answer"]));
//	if ($answer["answer_comments"]) {
//	  echo("<a href=\"\" title=\"".$answer["answer_comments"]." \">");
//	  echo("<img src=\"images/mark.gif\" border=0>");
//	  echo("</a>\n");
//	}

	if ($answer["answer_comments"]) {
	  echo("<a href=\"\" title=\"".$answer["answer_comments"]." \">");
	  echo(answer_from_id($answer["answer_answer"]));
	  echo("</a>\n");
	} else {
	  echo(answer_from_id($answer["answer_answer"]));
	}
	echo("</td>\n");
      } else {
 	echo("<td class=\"contentcell\">".answer_from_id(0)."</td>\n");
      }
      if ($i < ($gigsOnPage-1)) {
	echo("<td class=\"borderstripe\"></td>\n");
      }

    }

    echo("<td class=\"rightborderstripe\"></td>\n");
    echo("</tr>\n");
  }
  } else { // nothing scheduled!
    echo("<tr>\n");
    echo("<td class=\"leftborderstripe\"></td>\n");
    echo("<td class=\"noeventscell\">");
    echo("nothing scheduled!<br>");
    echo("</td>");
    echo("<td class=\"rightborderstripe\"></td>\n");
    echo("</tr>\n");
  }
  echo("<tr>\n");
  echo("<td><img src=\"images/llcorner.gif\" height=10 width=10></td>\n");

  echo("<td class=\"bottomborderstripe\"></td>\n");
  for($i=0; $i<count($theEventIds); $i++) {
    echo("<td class=\"bottomborderstripe\"></td>\n");
    if ($i < ($gigsOnPage-1)) {
      echo("<td class=\"borderstripe\"></td>\n");
    }
  }

//  echo("<td colspan=".(2*$gigsOnPage)." class=\"bottomborderstripe\"></td>\n");

  echo("<td><img src=\"images/lrcorner.gif\" height=10 width=10></td>\n");
  echo("</tr>");

  echo("</table>\n");

  echo("<br><br>");

  echo("<a href=\"eventedit.php\">[Add a Gig]</a>\n");
  echo("<a href=\"personedit.php\">[Add a Person]</a>\n");
  echo("<a href=\"listoldevents.php\">[See Past Gigs]</a><br>\n");

}

?>