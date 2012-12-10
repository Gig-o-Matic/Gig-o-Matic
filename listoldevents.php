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
$page_title="Old Gigs & Stuff";
include("html_header.inc");
makepage();
include("html_footer.inc");
close_database_link();


function makepage()
{
	echo("<h1>Old Events</h1>");

	$oldEvents=get_all_past_events();

	echo("<table cellspacing=0 cellpadding=0>\n");


	while($event=mysql_fetch_assoc($oldEvents)) {
		echo("<tr>\n");
			
		echo("<td class=\"oldgigdatecell\">".format_date_for_past_display($event["event_date"])."</td>\n");
		echo("<td>");
		echo(make_event_link($event));
		echo("</td>\n");
		echo("</tr>\n");
	}


	echo("</table>");
}


?>