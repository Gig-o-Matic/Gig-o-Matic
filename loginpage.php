<?php
session_start();
include_once("settings.php");
include_once("debug.php");
include_once("nocache.php");
include_once("functions.php");

$errorstr="";

if (isset($_SESSION["just_logged_in"])) {
  unset($_SESSION["just_logged_in"]);
  if ( !isset($_SESSION["user"]) ) {
    if (isset($_POST["login_id"])) {
	if ($_POST["login_id"]==$site_username && $_POST["login_pwd"]==$site_password) {
	      $_SESSION["user"]=$_POST["login_id"];
	      if (isset($_SESSION["original_url"])) {
		$str=$_SESSION["original_url"];
	      	unset($_SESSION["original_url"]);
	      	header("Location: ".$str); /* Redirect browser */
	      } else {
	    	header("Location: index.php"); /* Redirect browser */
	      }
	      exit; // make sure the original page doesn't run
	} else {
	   $errorstr="Wrong login/password.";
        }
    }
  }
}

if (isset($_GET["logout"])) {
//  setcookie("uresource",0);
//  unset($_COOKIE["uresource"]);
}

global $page_title;
$page_title="Log In";
include("html_simpleheader.inc");
makepage($errorstr);
include("html_simplefooter.inc");

function makepage($errorstr)
{
  unset($_SESSION["user"]);

  echo("<br><br>\n");

  open_database_link();

echo($errorstr."<br>");

  $_SESSION["just_logged_in"]=1;
  echo("<form action=\"loginpage.php\" method=\"POST\">\n");
  echo("Please log in: <br>");
  echo("user:<input type=\"text\" name=\"login_id\">\n");
  echo("pwd:<input type=\"password\" name=\"login_pwd\">\n");
  echo("<INPUT TYPE=\"submit\" value=\"go\"><br>\n");
  echo("</form>\n");

  close_database_link();
}

?>