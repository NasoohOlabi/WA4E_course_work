<?php 
function flashSessionAttribute ($str,$color = "green"){
	if ( isset($_SESSION[$str]) ) {
		echo("<p style=\"color:$color\">".$_SESSION[$str]."</p>\n");
		unset($_SESSION[$str]);
	}  
}
$ACCESS_DENIED = "<!DOCTYPE html>
<html>
<head>
<title>ACCESS DENIED</title>
</head>
<body>
ACCESS DENIED
</body>
</html>";

?>