<?php

function open_database_link() {
  global $database_link,$db_user,$db_password,$db_name;
/* Connecting, selecting database */
// for some reason, I can't get the password to work on my dev system, so just using root...
//  $database_link = mysql_connect("localhost", $db_user, $db_password)
//  $database_link = mysql_connect("localhost", "root", "root")
//     or die("Could not connect : " . mysql_error());
  $database_link = mysql_connect("10.6.171.92", $db_user, $db_password)
     or die("Could not connect : " . mysql_error());

  mysql_select_db($db_name) or die("Could not select database");
}

function close_database_link() {
  global $database_link;
  /* Closing connection */
  mysql_close($database_link);
}

function person_from_id ($theId) {
  $query = "SELECT * FROM person WHERE person_id=$theId";
  $result = mysql_query($query);
  
  if ($result) {
    if (mysql_num_rows($result)==0){
        $line=NULL;
    } else if (mysql_num_rows($result)>1) {
      die("wrong number of people for id: ".mysql_num_rows($result));
    } else {
        $line = mysql_fetch_assoc($result);
    } 
    /* Free resultset */
    mysql_free_result($result);

  } else {
      $line=NULL;
  }
  return $line;
}

// get all active members
function get_all_people ($is_old_event=FALSE) {

	if ($is_old_event) {
		$query = "SELECT * FROM person ORDER BY person_instrument";
	} else {
		$query = "SELECT * FROM person WHERE person_active=1 ORDER BY person_instrument";
	}

  $result = mysql_query($query);
  
  return $result;
}

function get_all_people_alpha() {
	$query = "SELECT * FROM person WHERE person_active=1 ORDER BY person_name";
	$result = mysql_query($query);
  
	return $result;
}

function event_from_id ($theId) {

  $query = "SELECT * FROM event,person WHERE event_id=$theId AND event_contact=person_id";
  $result = mysql_query($query);
  
  if (mysql_num_rows($result)!=1){
	die("wrong number of projects: ".mysql_num_rows($result));
  } 

  $line = mysql_fetch_assoc($result);
  
  /* Free resultset */
  mysql_free_result($result);

  return $line;
}

function get_all_events () {
  $query = "SELECT *,(event_date>=CURDATE()) as event_upcoming FROM event ORDER BY event_upcoming,event_date,event_id";
  $result = mysql_query($query);
  
  return $result;
}

function get_all_future_events () {
  $query = "SELECT * FROM event WHERE (event_date>=CURDATE()) ORDER BY event_date,event_id";
  $result = mysql_query($query);
  
  return $result;
}

function get_all_future_events_for_month ($month,$year) {
  $query = "SELECT * FROM event WHERE (event_date>=CURDATE()) AND (MONTH(event_date)=".$month.") AND (YEAR(event_date)=".$year.") ORDER BY event_date,event_id";
  $result = mysql_query($query);
  
  return $result;
}

function get_all_past_events() {
  $query = "SELECT * FROM event WHERE (event_date<CURDATE()) ORDER BY event_date DESC,event_id";
  $result = mysql_query($query);
  
  return $result;
}

function get_answer($thePerson,$theEvent) {
  $query = "SELECT * FROM answer WHERE answer_person=".$thePerson." AND answer_event=".$theEvent;
  $result=mysql_query($query);
   return $result;

}

function get_all_confirmed()
{
  $ret=array(0=>"not confirmed",1=>"confirmed");
  return $ret;
}

function confirmed_from_id($theId)
{
  $ret="";
  switch($theId) {
  case 0: $ret="not confirmed"; break;
  case 1: $ret="confirmed"; break;
  }
  return $ret;
}

function get_all_answers()
{
  $ret=array(0=>"-",1=>"yes",2=>"yes?",3=>"?",4=>"no?",5=>"no",6=>"see note!");
  return $ret;
}

function answer_from_id($theId) {
  $ret="";
  switch($theId) {
  case 0: $ret="-"; break;
  case 1: $ret="yes"; break;
  case 2: $ret="yes?"; break;
  case 3: $ret="?"; break;
  case 4: $ret="no?"; break;
  case 5: $ret="no"; break;
  case 6: $ret="see note!"; break;
  }
  return $ret;
}


// If you change this one, change the next one too...
function get_all_instruments()
{
  $ret=array(0=>"?",10=>"sax",15=>"clarinet",20=>"trumpet",30=>"trombone",35=>"euphonium",40=>"tuba",50=>"drums",60=>"banjo",70=>"accordion",80=>"other",999=>"sub");
  return $ret;
}

function instrument_from_id($theId) {
  $ret="";
  switch($theId) {
  case 0: $ret="?"; break;
  case 10: $ret="sax"; break;
  case 15: $ret="clarinet"; break;
  case 20: $ret="trumpet"; break;
  case 30: $ret="trombone"; break;
  case 35: $ret="euphonium"; break;
  case 40: $ret="tuba"; break;
  case 50: $ret="drums"; break;
  case 60: $ret="banjo"; break;
  case 70: $ret="accordion"; break;
  case 80: $ret="other"; break;
  case 999: $ret="sub"; break;
  }
  return $ret;
}

function parse_date($theDateString)
{
  $dateParts=explode("-",$theDateString);
  $dayofweek=date("l", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
 $ret=array("year"=>$dateParts[0],"month"=>$dateParts[1],"day"=>$dateParts[2],"dayofweek"=>$dayofweek);
  return $ret;
}

function format_date_for_display($theDateString)
{
  $parts=parse_date($theDateString);
  return ltrim($parts["month"],"0")."/".ltrim($parts["day"],"0")."/".$parts["year"]."&nbsp;(".$parts["dayofweek"].")";
}

function format_date_for_short_display($theDateString)
{
  $parts=parse_date($theDateString);
  return ltrim($parts["month"],"0")."/".ltrim($parts["day"],"0")." (".$parts["dayofweek"].")";
}

function format_date_for_past_display($theDateString)
{
  $parts=parse_date($theDateString);
  return ltrim($parts["month"],"0")."/".ltrim($parts["day"],"0")."/".$parts["year"];
}

function format_date_for_database($m,$d,$y) {
  return $y."-".$m."-".$d;
}

function is_old_event($event) {
	$theDate=$event["event_date"];
	
	$eventparts=parse_date($theDate);
	$t=time();
	$todayparts=array("year"=>gmdate('Y',$t),"month"=>gmdate('m',$t),"day"=>gmdate('d',$t));
	

	if($eventparts["year"]<$todayparts["year"]) { 
		// happened in previous year
		return TRUE; 
	} else if (($eventparts["year"]==$todayparts["year"]) && ($eventparts["month"]<$todayparts["month"])) {
		// this year, but previous month
		return TRUE;
	} else if (($eventparts["year"]==$todayparts["year"]) && ($eventparts["month"]==$todayparts["month"]) && ($eventparts["day"]<$todayparts["day"])) {
		// this year, this month, previous day
		return TRUE;
	}
	
//	if($eventparts["month"]<$todayparts["month"]) { return TRUE; }
//	if($eventparts["day"]<$todayparts["day"]) { return TRUE; }

	// in the end, if must be a future event.
	return FALSE;

}

function make_person_link($line) {
 return "<a href=\"person.php?id=".$line["person_id"]."\">".$line["person_name"]."</a>";
}

function make_event_link($line) {
 return "<a href=\"event.php?id=".$line["event_id"]."\">".$line["event_name"]."</a>";
}

function update_answer($person,$event,$answer,$comments) {
  $comments=mysql_escape_string(stripslashes($comments));
  $query="update answer SET answer_answer=".$answer.",answer_comments=\"".$comments."\" WHERE (answer_person=".$person." AND answer_event=".$event.")";
  $result = mysql_query($query);
  if (mysql_affected_rows()==0) {
	// update failed - do an insert
	$query="insert into answer SET answer_answer=".$answer.",answer_comments=\"".$comments."\",answer_person=".$person.",answer_event=".$event;
        $result = mysql_query($query);
  }
}
function month_from_number($theId)
{
  $ret="";
  switch($theId) {
  case 1: $ret="January"; break;
  case 2: $ret="February"; break;
  case 3: $ret="March"; break;
  case 4: $ret="April"; break;
  case 5: $ret="May"; break;
  case 6: $ret="June"; break;
  case 7: $ret="July"; break;
  case 8: $ret="August"; break;
  case 9: $ret="September"; break;
  case 10: $ret="October"; break;
  case 11: $ret="November"; break;
  case 12: $ret="December"; break;
  }
  return $ret;
}

function get_default_event_details()
{
  return "Useful info: directions, outfit, musical directions, setlist, length of gig, emergency cell phone #...";
}