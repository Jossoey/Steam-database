<?php
/*
* ╔═════════════════════╗
* ║       Logout        ║
* ╚═════════════════════╝
* Should include all logout and session cleanup information
*/
	$e = session_start();
	echo var_export($e);
	
// Unset all current session data
	$_SESSION = array();

// Delete current session
//	if(session_destroy()) {
		header("Location: login.php");
		exit();
//	}

?>
