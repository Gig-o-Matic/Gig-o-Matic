<html>
<head>
 <title><?php global $page_title; echo($page_title);?></title>

<script language="javascript" type="text/javascript">
<!--Hide script from old browsers

function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

var http = createRequestObject();

function sndReq(action) {
    http.open('get', 'rpc.php?action='+action);
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function sndAnswerReq(personId,eventId) {
    var theAnswer=document.getElementById("@"+personId+"@"+eventId).value;
    var theComments=document.getElementById("#"+personId+"#"+eventId).value;
    http.open("get", "answerupdate-rpc.php?action=answer&personId="+personId+"&eventId="+eventId+"&theAnswer="+theAnswer+"&theComments="+theComments);
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function handleResponse() {
    if(http.readyState == 4){
document.getElementById("foo").innerHTML=http.responseText;
        var response = http.responseText;
        var update = new Array();

        if(response.indexOf('|' != -1)) {
            update = response.split('|');
            document.getElementById(update[0]).innerHTML = update[1];
        }
    }
}

function HideContent(d) {
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
document.getElementById(d).style.display = "block";
}

//-->
 </SCRIPT>

<link rel="stylesheet" href="style.css">
</head>
<body>

<table width=1000 cellspacing=0 cellpadding=0>
<tr bgcolor="#235D85" height=50><td><img src="images/title.gif"></td></tr>
</table>

<div id='content'>
<a href="index.php">home</a>&nbsp;&nbsp;<a href="loginpage.php?logout=1">logout</a>

