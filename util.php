function flashSessionAttribute ($str,$color = "green"){
	if ( isset($_SESSION[$str]) ) {
		echo("<p style=\"color:$color\">".$_SESSION[$str]."</p>\n");
		unset($_SESSION[$str]);
	}  
}