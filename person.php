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
$page_title="Person Detail";
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

  // if no person selected, bail out now
  if ($theId==0) {
    return;
  }

  $thePerson=person_from_id($theId);
  echo("<h1>".$thePerson["person_name"]."</h1>");

  echo("<table cellspacing=0 cellpadding=0>\n");

  echo("<tr>\n");
  echo("<td class=\"eventheadercell\">phone</td>\n");
  echo("<td class=\"eventinfocell\">".$thePerson["person_phone"]."</td>\n");
  echo("</tr>\n");

  echo("<td class=\"eventheadercell\">email</td>\n");
  $tmp=($thePerson["person_email"]=="")?"":"<a href=\"mailto:".$thePerson["person_email"]."\">".$thePerson["person_email"]."</a>";
  echo("<td class=\"eventinfocell\">".$tmp."</td>\n");
  echo("</tr>\n");

  echo("<td class=\"eventheadercell\">instrument</td>\n");
  echo("<td class=\"eventinfocell\">".instrument_from_id($thePerson["person_instrument"])."</td>\n");
  echo("</tr>\n");

  echo("</table>\n");

  echo("<a href=personedit.php?id=".$theId.">[edit]</a>");

  echo("<br><br><br>\n");

  echo("<form id=\"answerForm\">\n");
  echo("<table cellspacing=0 cellpadding=0>\n");

  $allGigs=get_all_future_events();
  $allAnswers=get_all_answers();
  $currentMonth=0;
  while($event=mysql_fetch_assoc($allGigs)) {

	$dateParsed=parse_date($event["event_date"]);
	if ($dateParsed["month"]<>$currentMonth) {
	 	$currentMonth=$dateParsed["month"];
		echo("<tr>\n");
		echo("<td>");
		echo("<a href=\"index.php?month=".$currentMonth."&year=".$dateParsed["year"]."\">".month_from_number($currentMonth)."</a>\n");
		echo("</td>\n");
		echo("</tr>\n");		
	}
	echo("<tr>\n");
	echo("<td></td>");
	echo("<td>");
	echo(format_date_for_display($event["event_date"]));
	echo("</td>\n");
	echo("<td class=\"personspacer\"></td>");
	echo("<td>");
	if ($event["event_call"]) {
		echo($event["event_call"]."&nbsp;call");
	} else {
		echo("call&nbsp;TBD");
	}
	echo("</td>\n");
	echo("<td class=\"personspacer\"></td>");
	echo("<td>");
	echo(make_event_link($event));
	echo("</td>\n");

	echo("<td class=\"personanswercell\">");

	$theAnswer=get_answer($theId,$event["event_id"]);
	$answer=mysql_fetch_assoc($theAnswer);

	echo("<select onchange='sndAnswerReq(".$theId.",".$event["event_id"].");' id=\"@".$theId."@".$event["event_id"]."\">\n");
	foreach($allAnswers as $answerId=>$answerName) {
	  echo("<option value=".$answerId);
	  if ($answerId==$answer["answer_answer"]) {
	    echo(" SELECTED");
	  }
	  echo(">".$answerName."<br>\n");
	}
	echo("</select>\n");
	echo("</td>\n");
	echo("<td class=\"personcommentcell\">");
	echo("<input type=\"text\" size=40 onblur='sndAnswerReq(".$theId.",".$event["event_id"].");' id=\"#".$theId."#".$event["event_id"]."\" value=\"".$answer["answer_comments"]."\">");
	echo("</td>\n");
	echo("</tr>\n");
  }

  echo("<tr height=50><td></td><td></td><td></td><td></td><td></td><td>");
  echo("<input type=\"button\" value=\"update availability & comments\"\n");
  echo("</td></tr>\n");

  echo("</table>\n");

//  echo("<input type=\"hidden\" name=\"returnto\" value=\"person.php?id=".$theId."\">");
  echo("</form>\n");
  echo("</div>\n"); // editcontent

}

?>
