<?php
// stuff for making sure we're logged in - check_login_state will hijack the 
// pagebuild if you're not logged in.

function check_login_state()
{
  
  if ( !isset($_SESSION["user"]) ) {
    $_SESSION["original_url"]=$_SERVER["REQUEST_URI"];
    header("Location: loginpage.php"); /* Redirect browser */
    exit; // make sure the original page doesn't run
  }
}

if (!function_exists('http_build_query')) {
  function http_build_query($a) {
    $f = '';$ret = '';
    foreach ($a as $i => $j) { // might wanna slop urlencode() in here
      $ret .= "$f$i=$j"; $f='&';
    }
    return $ret;
  }
}

?>